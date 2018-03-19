<?php

namespace Larrock\Core\Tests\Traits;

use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\Models\Config;
use Larrock\Core\Models\Link;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateLinkDatabase;
use Orchestra\Testbench\TestCase;
use DB;

class GetLinkTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp()
    {
        parent::setUp();

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        $seed = new CreateLinkDatabase();
        $seed->setUpLinkDatabase();

        DB::connection()->table('blocks')->insert([
            'title' => 'test2',
            'description' => 'test2',
            'url' => 'test2',
        ]);

        DB::connection()->table('link')->insert([
            'id_parent' => 1,
            'id_child' => 2,
            'model_parent' => Blocks::class,
            'model_child' => Blocks::class
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    public function testLinkQuery()
    {
        $model = Blocks::whereId(1)->first();
        $result = $model->linkQuery(Blocks::class)->first();
        $this->assertInstanceOf(Link::class, $result);
        $this->assertEquals(1, $result->id_parent);
        $this->assertEquals(2, $result->id_child);
        $this->assertEquals(Blocks::class, $result->model_parent);
        $this->assertEquals(Blocks::class, $result->model_child);
    }

    public function testLink()
    {
        $model = Blocks::whereId(1)->first();
        $result = $model->link(Blocks::class)->first();
        $this->assertInstanceOf(Link::class, $result);
        $this->assertEquals(1, $result->id_parent);
        $this->assertEquals(2, $result->id_child);
        $this->assertEquals(Blocks::class, $result->model_parent);
        $this->assertEquals(Blocks::class, $result->model_child);
    }

    public function testLinkParam()
    {
        DB::connection()->table('link')->insert([
            'id_parent' => 1,
            'id_child' => 2,
            'model_parent' => Blocks::class,
            'model_child' => 'Larrock\ComponentCatalog\Models\Param'
        ]);

        $model = Blocks::whereId(1)->first();
        $result = $model->linkParam(Blocks::class)->first();
        $this->assertInstanceOf(Link::class, $result);
        $this->assertEquals(1, $result->id_parent);
        $this->assertEquals(2, $result->id_child);
        $this->assertEquals(Blocks::class, $result->model_parent);
        $this->assertEquals('Larrock\ComponentCatalog\Models\Param', $result->model_child);
    }

    public function testLinkAttribute()
    {
        $model = Blocks::whereId(1)->first();
        $result = $model->link(Blocks::class)->first();
        $this->assertInstanceOf(Link::class, $result);
        $this->assertEquals(1, $result->id_parent);
        $this->assertEquals(2, $result->id_child);
        $this->assertEquals(Blocks::class, $result->model_parent);
        $this->assertEquals(Blocks::class, $result->model_child);
    }

    public function testGetLink()
    {
        $model = Blocks::whereId(1)->first();
        $result = $model->getLink(Blocks::class)->first();
        $this->assertInstanceOf(Blocks::class, $result);
        $this->assertEquals(2, $result->id);
    }

    public function testGetLinkWithParams()
    {
        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        DB::connection()->table('link')->insert([
            'id_parent' => 1,
            'id_child' => 1,
            'model_parent' => Blocks::class,
            'model_child' => Config::class
        ]);

        $model = Blocks::whereId(1)->first();
        $result = $model->getLinkWithParams(Config::class, 'blocks', 'id', 'id')->first();
        $this->assertInstanceOf(Config::class, $result);
        $this->assertEquals(1, $result->id);
    }

    public function testGetAllLinks()
    {
        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        DB::connection()->table('link')->insert([
            'id_parent' => 1,
            'id_child' => 1,
            'model_parent' => Blocks::class,
            'model_child' => Config::class
        ]);

        $model = Blocks::whereId(1)->first();
        $result = $model->getAllLinks()->get();

        $this->assertCount(2, $result);
    }

    public function testGetCostLink()
    {
        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        DB::connection()->table('link')->insert([
            'id_parent' => 1,
            'id_child' => 1,
            'model_parent' => Blocks::class,
            'model_child' => Config::class,
            'cost' => 100
        ]);

        $model = Blocks::whereId(1)->first();
        $result = $model->getCostLink(Config::class)->first();
        $this->assertEquals(100, $result->cost);
    }

    public function testGetCostValuesAttribute()
    {
        $model = Blocks::whereId(1)->first();
        $this->assertNull($model->cost_values);
    }

    public function testGetFirstCostValueAttribute()
    {
        $model = Blocks::whereId(1)->first();
        $this->assertNull($model->first_cost_value);
    }

    public function testGetFirstCostValueIdAttribute()
    {
        $model = Blocks::whereId(1)->first();
        $this->assertNull($model->first_cost_value_id);
    }

    public function testGetFirstCostValueTitleAttribute()
    {
        $model = Blocks::whereId(1)->first();
        $this->assertNull($model->first_cost_values_title);
    }
}