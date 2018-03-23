<?php

namespace Larrock\Core\Tests\Helpers\Plugins;

use Illuminate\Http\Request;
use Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\Events\ComponentItemStored;
use Larrock\Core\Plugins\ComponentPlugin;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;
use Orchestra\Testbench\TestCase;

class ComponentPluginTest extends TestCase
{
    /** @var ComponentPlugin */
    protected $componentPlugin;

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

        $this->componentPlugin = new ComponentPlugin();

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockComponentAdminSeoServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks',
            'LarrockAdminSeo' => 'Larrock\ComponentAdminSeo\Facades\LarrockSeo'
        ];
    }

    public function testAttach()
    {
        $component = new BlocksComponent();
        $request = Request::create('/', 'POST', [
            'seo_title' => 'seo_title_test',
            'seo_description' => 'seo_description_test',
            'seo_seo_keywords' => 'seo_keywords_test',
            'type_connect' => 'blocks',
            'id_connect' => 1
        ]);
        $data = Blocks::whereId(1)->first();
        $event = new ComponentItemStored($component, $data, $request);
        $test = $this->componentPlugin->attach($event);
        $this->assertNull($test);

        /*$testData = Blocks::whereTypeConnect('blocks')->whereIdConnect(1)->first();
        dd($testData);*/
    }
}