<?php

namespace Larrock\Core\Tests\Helpers\Plugins;

use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
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

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        $seed = new CreateMediaDatabase();
        $seed->setUpMediaDatabase();
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
        DB::connection()->table('blocks')->insert([
            'title' => 'test',
            'description' => 'test',
            'url' => 'render',
        ]);

        $this->RenderPlugins = new RenderPlugins('{Блок[default]=render}', 'model');
        $test = $this->RenderPlugins->renderBlocks();
        $this->assertNotEmpty($test->rendered_html);
    }

    public function testRenderImageGallery()
    {
        $model = new Blocks();
        $this->RenderPlugins = new RenderPlugins('{Фото[news]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderImageGallery();
        $this->assertNotEmpty($test->rendered_html);

        $this->RenderPlugins = new RenderPlugins('{Фото[nonFancy]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderImageGallery();
        $this->assertNotEmpty($test->rendered_html);

        $this->RenderPlugins = new RenderPlugins('{Фото[newsDescription]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderImageGallery();
        $this->assertNotEmpty($test->rendered_html);

        $this->RenderPlugins = new RenderPlugins('{Фото[blocks]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderImageGallery();
        $this->assertNotEmpty($test->rendered_html);

        $this->RenderPlugins = new RenderPlugins('{Фото[blocksBig]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderImageGallery();
        $this->assertNotEmpty($test->rendered_html);

        $this->RenderPlugins = new RenderPlugins('{Фото[sert]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderImageGallery();
        $this->assertNotEmpty($test->rendered_html);
    }

    public function testRenderFilesGallery()
    {
        $model = new Blocks();
        $this->RenderPlugins = new RenderPlugins('{Файлы[default]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderFilesGallery();
        $this->assertNotEmpty($test->rendered_html);

        $this->RenderPlugins = new RenderPlugins('{Файлы[price]=render}', $model->find(1));
        $test = $this->RenderPlugins->renderFilesGallery();
        $this->assertNotEmpty($test->rendered_html);
    }
}