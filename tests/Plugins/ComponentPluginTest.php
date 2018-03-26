<?php

namespace Larrock\Core\Tests\Helpers\Plugins;

use Illuminate\Http\Request;
use Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\Events\ComponentItemDestroyed;
use Larrock\Core\Events\ComponentItemStored;
use Larrock\Core\Helpers\FormBuilder\FormSelect;
use Larrock\Core\Helpers\FormBuilder\FormTags;
use Larrock\Core\Plugins\ComponentPlugin;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateLinkDatabase;
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

        $seed = new CreateLinkDatabase();
        $seed->setUpLinkDatabase();
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
        \DB::connection()->table('blocks')->insert([
            'title' => 'test2',
            'description' => 'test2',
            'url' => 'test2',
        ]);

        $component = new BlocksComponent();
        $row = new FormTags('link', 'title');
        $component->rows['link'] = $row->setModels(Blocks::class, Blocks::class);

        //Случай когда нужно seo создать
        $request = Request::create('/', 'POST', [
            'seo_title' => 'seo_title_test',
            'seo_description' => 'seo_description_test',
            'seo_seo_keywords' => 'seo_keywords_test',
            'type_connect' => 'blocks',
            'id_connect' => 1,
            'link' => [2]
        ]);
        $data = Blocks::whereId(1)->first();
        $event = new ComponentItemStored($component, $data, $request);
        $this->componentPlugin->attach($event);

        $this->assertCount(1, \DB::connection()->table('link')->get());

        //Случай когда seo нужно удалить
        \DB::connection()->table('seo')->insert([
            'seo_title' => 'test',
            'seo_description' => 'test',
            'seo_keywords' => 'test',
            'seo_id_connect' => 1,
            'seo_url_connect' => 'test',
            'seo_type_connect' => 'blocks',
        ]);

        $request = Request::create('/', 'POST', [
            'seo_title' => 'seo_title_test',
            'seo_description' => 'seo_description_test',
            'seo_seo_keywords' => 'seo_keywords_test',
            'type_connect' => 'blocks',
            'id_connect' => 1
        ]);
        $event = new ComponentItemStored($component, $data, $request);
        $this->componentPlugin->attach($event);

        //Случай когда с сео ничего не должно произойти
        $request = Request::create('/', 'POST');
        $event = new ComponentItemStored($component, $data, $request);
        $test = $this->componentPlugin->attach($event);

        $this->assertNull($test);

    }

    public function testDetach()
    {
        \DB::connection()->table('seo')->insert([
            'seo_title' => 'test',
            'seo_description' => 'test',
            'seo_keywords' => 'test',
            'seo_id_connect' => 1,
            'seo_url_connect' => 'test',
            'seo_type_connect' => 'blocks',
        ]);

        \DB::connection()->table('blocks')->insert([
            'title' => 'test2',
            'description' => 'test2',
            'url' => 'test2',
        ]);

        \DB::connection()->table('link')->insert([
            'id_parent' => '1',
            'id_child' => '2',
            'model_parent' => Blocks::class,
            'model_child' => Blocks::class,
        ]);

        $this->assertCount(1, \DB::connection()->table('link')->get());

        $component = new BlocksComponent();
        $row = new FormTags('link', 'title');
        $component->rows['link'] = $row->setModels(Blocks::class, Blocks::class)->setDeleteIfNoLink();

        $request = Request::create('/', 'POST');
        $data = Blocks::whereId(1)->first();
        $event = new ComponentItemDestroyed($component, $data, $request);
        $test = $this->componentPlugin->detach($event);
        $this->assertNull($test);

        $this->assertCount(1, \DB::connection()->table('seo')->get());

        $this->assertCount(0, \DB::connection()->table('link')->get());
        $this->assertCount(1, \DB::connection()->table('blocks')->get());
    }
}