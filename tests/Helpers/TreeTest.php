<?php

namespace Larrock\Core\Tests\Helpers;

use Larrock\Core\Helpers\Tree;
use Larrock\Core\Tests\DatabaseTest\CreateCategoryDatabase;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\DB;

class TreeTest extends TestCase
{
    /** @var Tree */
    protected $tree;

    protected function setUp()
    {
        parent::setUp();

        $this->tree = new Tree();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->tree);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    public function testBuildTree()
    {
        $seed = new CreateCategoryDatabase();
        $seed->setUpCategoryDatabase();

        DB::connection()->table('category')->insert([
            'title' => 'test2',
            'short' => 'test2',
            'description' => 'test2',
            'component' => 'test',
            'parent' => 1,
            'level' => 2,
            'url' => 'test2',
        ]);

        $data = DB::table('category')->get();
        $test = $this->tree->buildTree($data, 'parent');
        $this->assertCount(1, $test[0]->children);
    }

    public function testListActiveCategories()
    {
        $seed = new CreateCategoryDatabase();
        $seed->setUpCategoryDatabase();

        DB::connection()->table('category')->insert([
            'title' => 'test2',
            'short' => 'test2',
            'description' => 'test2',
            'component' => 'test',
            'parent' => 1,
            'level' => 2,
            'url' => 'test2',
            'active' => 1
        ]);

        DB::connection()->table('category')->insert([
            'title' => 'test3',
            'short' => 'test3',
            'description' => 'test3',
            'component' => 'test',
            'parent' => 2,
            'level' => 3,
            'url' => 'test3',
            'active' => 0
        ]);

        $data = DB::table('category')->where('id', '=', 1)->get();

        $data[0]->get_childActive = collect();
        $parent = DB::table('category')->where('id', '=', 2)->first();
        $parent->get_childActive = null;
        $data[0]->get_childActive->push($parent);

        $this->assertEquals([0 => '1', 1 => '2'], $this->tree->listActiveCategories($data));
    }
}