<?php

namespace Larrock\Core\Tests\Helpers\Plugins;

use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Orchestra\Testbench\TestCase;
use Larrock\Core\Helpers\Plugins\RenderPlugins;
use Illuminate\Support\Facades\DB;

class RenderPluginsTest extends TestCase
{
    /** @var RenderPlugins */
    protected $RenderPlugins;

    protected function setUp()
    {
        parent::setUp();

        $this->RenderPlugins = new RenderPlugins('html', 'model');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->RenderPlugins);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks'
        ];
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

    public function testRenderBlocks()
    {
        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        DB::connection()->table('blocks')->insert([
            'title' => 'test',
            'description' => 'test',
            'url' => 'render',
        ]);

        $this->RenderPlugins = new RenderPlugins('{Блок[default]=render}', 'model');
        $test = $this->RenderPlugins->renderBlocks();
        $this->assertNotEmpty($test->rendered_html);
    }
}