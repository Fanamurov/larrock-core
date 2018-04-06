<?php

namespace Larrock\Core\Tests\Traits;

use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\Models\Seo;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;
use Orchestra\Testbench\TestCase;
use DB;

class GetSeoTest extends TestCase
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

        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();

        DB::connection()->table('blocks')->insert([
            'title' => 'test2',
            'description' => 'test2',
            'url' => 'test2',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    public function testGetSeo()
    {
        $model = Blocks::whereId(2)->first();
        $result = $model->getSeo()->first();
        $this->assertNull($result);

        DB::connection()->table('seo')->insert([
            'seo_title' => 'test_seo',
            'seo_description' => 'test_seo',
            'seo_keywords' => 'test_seo',
            'seo_id_connect' => 2,
            'seo_url_connect' => 'test_seo',
            'seo_type_connect' => 'blocks',
        ]);

        $result = $model->getSeo()->first();
        $this->assertEquals('test_seo', $result->seo_title);
        $this->assertEquals('test_seo', $result->seo_description);
        $this->assertEquals('test_seo', $result->seo_keywords);
        $this->assertEquals(2, $result->seo_id_connect);

        DB::connection()->table('seo')->where('id', '=', 2)->delete();
        DB::connection()->table('seo')->insert([
            'seo_title' => 'test_seo2',
            'seo_description' => 'test_seo2',
            'seo_keywords' => 'test_seo2',
            'seo_id_connect' => 2,
            'seo_url_connect' => 'test2',
            'seo_type_connect' => 'blocks',
        ]);

        $result = $model->getSeo()->first();
        $this->assertEquals('test_seo2', $result->seo_title);
        $this->assertEquals('test_seo2', $result->seo_description);
        $this->assertEquals('test_seo2', $result->seo_keywords);
        $this->assertEquals(2, $result->seo_id_connect);
    }

    public function testGetGetSeoTitleAttribute()
    {
        //Возвращение чистого title материала, когда нет сео-записи
        $model = Blocks::whereId(1)->first();
        $this->assertEquals('test', $model->get_seo_title);
        \Cache::flush();

        //Возвращение сео-записи по url
        DB::connection()->table('seo')->insert([
            'seo_title' => 'test_url',
            'seo_description' => 'test_url',
            'seo_keywords' => 'test_url',
            'seo_id_connect' => 1,
            'seo_url_connect' => 'test',
            'seo_type_connect' => 'blocks',
        ]);
        $model = Blocks::whereId(1)->first();
        $this->assertEquals('test_url', $model->get_seo_title);

        //Проверка возвращения существующей сео-записи
        DB::connection()->table('seo')->insert([
            'seo_title' => 'test_seo',
            'seo_description' => 'test_seo',
            'seo_keywords' => 'test_seo',
            'seo_id_connect' => 2,
            'seo_url_connect' => 'test_seo',
            'seo_type_connect' => 'blocks',
        ]);

        $model = Blocks::whereId(2)->first();
        $this->assertEquals('test_seo', $model->get_seo_title);
    }
}