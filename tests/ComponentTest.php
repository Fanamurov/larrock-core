<?php

namespace Larrock\Core\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Larrock\Core\Component;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Models\Config;
use Larrock\Core\Tests\DatabaseTest\CreateLinkDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;

class ComponentTest extends \Orchestra\Testbench\TestCase
{
    /** @var Component */
    protected $component;

    protected function setUp()
    {
        parent::setUp();

        $this->component = new Component();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->component);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('larrock.middlewares.front', ['TestAddFront']);
        $app['config']->set('larrock.middlewares.admin', ['TestAddAdmin']);
        $app['config']->set('larrock.feed.anonsCategory', 1);
    }

    protected function getPackageProviders($app)
    {
        return [
            'Proengsoft\JsValidation\JsValidationServiceProvider',
            'Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider',
            //'Larrock\ComponentFeed\LarrockComponentFeedServiceProvider',
            'DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider'
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'JsValidator' => 'Proengsoft\JsValidation\Facades\JsValidatorFacade',
            'LarrockAdminSeo' => 'Larrock\ComponentAdminSeo\Facades\LarrockSeo',
            //'LarrockFeed' => 'Larrock\ComponentFeed\Facades\LarrockFeed',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs'
        ];
    }

    public function testGetConfig()
    {
        $attributes = ['name', 'title', 'description', 'table', 'rows', 'customMediaConversions', 'model', 'active',
            'plugins_backend', 'plugins_front', 'settings', 'searchable', 'tabs', 'tabs_data', 'valid'];
        foreach ($attributes as $attribute){
            $this->assertClassHasAttribute($attribute, \get_class($this->component->getConfig()));
        }
        $this->assertInstanceOf('Larrock\Core\Component', $this->component->getConfig());
    }

    public function testGetName()
    {
        $this->component->name = 'test';
        $this->assertEquals('test', $this->component->getName());
    }

    public function testGetTitle()
    {
        $this->component->title = 'test';
        $this->assertEquals('test', $this->component->getTitle());
    }

    public function testGetModel()
    {
        $this->component->model = Config::class;
        $this->assertInstanceOf(Model::class, $this->component->getModel());
    }

    public function testGetModelName()
    {
        $this->component->model = Config::class;
        $this->assertEquals('Larrock\Core\Models\Config', $this->component->getModelName());
    }

    public function testGetTable()
    {
        $this->component->table = 'test';
        $this->assertEquals('test', $this->component->getTable());
    }

    public function testGetRows()
    {
        $this->component->rows = [];
        $this->assertEquals([], $this->component->getRows());
    }

    public function testGetValid()
    {
        $row = new FormInput('title', 'Заголовок');
        $this->component->rows['title'] = $row->setValid('max:255|required')->setTypo()->setFillable();
        $this->assertEquals(['title' => 'max:255|required'], $this->component->getValid());
    }

    public function testGetFillableRows()
    {
        $row = new FormInput('title', 'Заголовок');
        $this->component->rows['title'] = $row->setFillable();
        $this->assertEquals([0 => 'title'], $this->component->getFillableRows());
    }

    public function testAddPosition()
    {
        $this->assertInstanceOf(Component::class, $this->component->addPosition());
        $this->assertObjectHasAttribute('rows', $this->component->addPosition());
        $this->assertArrayHasKey('position', $this->component->addPosition()->rows);
        $this->assertInstanceOf(FormInput::class, $this->component->addPosition()->rows['position']);
    }

    public function testAddActive()
    {
        $this->assertInstanceOf(Component::class, $this->component->addActive());
        $this->assertObjectHasAttribute('rows', $this->component->addActive());
        $this->assertArrayHasKey('active', $this->component->addActive()->rows);
        $this->assertInstanceOf(FormCheckbox::class, $this->component->addActive()->rows['active']);
    }

    public function testAddPositionAndActive()
    {
        $this->assertInstanceOf(Component::class, $this->component->addPositionAndActive());
        $this->assertObjectHasAttribute('rows', $this->component->addPositionAndActive());
        $this->assertArrayHasKey('position', $this->component->addPosition()->rows);
        $this->assertInstanceOf(FormInput::class, $this->component->addPosition()->rows['position']);
        $this->assertArrayHasKey('active', $this->component->addActive()->rows);
        $this->assertInstanceOf(FormCheckbox::class, $this->component->addActive()->rows['active']);
    }

    public function testShareConfig()
    {
        $this->assertInstanceOf(Component::class, $this->component->shareConfig());
        $this->assertInstanceOf(Collection::class, $this->component->shareConfig()->tabs);
        $this->assertInstanceOf(Collection::class, $this->component->shareConfig()->tabs_data);
    }

    public function testCombineFrontMiddlewares()
    {
        $this->assertEquals([0 => 'web', 1 => 'GetSeo', 2 => 'TestAddFront'], $this->component->combineFrontMiddlewares());
        $this->assertEquals([0 => 'web', 1 => 'GetSeo', 2 => 'TestAddFront', 3 => 'Test'], $this->component->combineFrontMiddlewares(['Test']));
    }

    public function testCombineAdminMiddlewares()
    {
        $this->assertEquals([0 => 'web', 1 => 'level:2', 2 => 'LarrockAdminMenu', 3 => 'SaveAdminPluginsData', 4 => 'SiteSearchAdmin', 5 => 'TestAddAdmin'],
            $this->component->combineAdminMiddlewares());
        $this->assertEquals([0 => 'web', 1 => 'level:2', 2 => 'LarrockAdminMenu', 3 => 'SaveAdminPluginsData', 4 => 'SiteSearchAdmin', 5 => 'TestAddAdmin', 6 => 'Test'],
            $this->component->combineAdminMiddlewares(['Test']));
    }

    /**
     * @throws \Exception
     */
    public function testSavePluginsData()
    {
        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();

        //Проверка на успешное внесение seo-данных
        $request = Request::create('', 'POST', [
            'id_connect' => 2,
            'seo_title' => 'seo_title_default',
            'seo_description' => 'seo_description_default',
            'seo_keywords' => 'seo_keywords_default',
            'type_connect' => 'type_connect_default',
            'url_connect' => 'url_connect_default'
        ]);

        $this->component->savePluginsData($request);
        $test = DB::connection()->table('seo')->where('id', '=', 2)->first();

        $this->assertEquals(2, $test->id);
        $this->assertEquals('seo_title_default', $test->seo_title);
        $this->assertEquals('seo_description_default', $test->seo_description);
        $this->assertEquals('seo_keywords_default', $test->seo_keywords);
        $this->assertEquals('type_connect_default', $test->seo_type_connect);
        $this->assertEquals('url_connect_default', $test->seo_url_connect);

        //Проверка на неполноту данных для внесения seo-данных
        $request = Request::create('', 'POST', [
            'id_connect' => 3,
            'seo_title' => 'seo_title_default',
            'seo_description' => 'seo_description_default',
            'seo_keywords' => 'seo_keywords_default',
            'url_connect' => 'url_connect_default'
        ]);

        $this->component->savePluginsData($request);
        $test = DB::connection()->table('seo')->where('id', '=', 3)->first();
        $this->assertNull($test);

        //Проверка на удаление сео-данных
        $request = Request::create('', 'POST', [
            'id_connect' => 1,
            'type_connect' => 'test',
            'seo_title' => '',
            'seo_description' => '',
            'seo_keywords' => '',
        ]);
        $this->component->savePluginsData($request);
        $test = DB::connection()->table('seo')->where('id', '=', 1)->first();
        $this->assertNull($test);


        //TODO:Проверка на внесение данных анонса
        //LarrockFeed не является частью LarrockCore
        /*$request = Request::create('', 'POST', [
            'anons_merge' => 1,
            'anons_description' => 'anons_description_default'
        ]);
        $this->component->savePluginsData($request);*/

        //Проверка на вставку связей
        $seed = new CreateLinkDatabase();
        $seed->setUpLinkDatabase();
        $request = Request::create('', 'POST', [
            'link' => [2],
            'modelParent' => 'Larrock\ComponentBlocks\Models\Blocks',
            'modelParentId' => 1,
            'modelChild' => 'Larrock\ComponentBlocks\Models\Blocks',
        ]);
        $this->component->savePluginsData($request);
        $test = DB::connection()->table('link')->first();
        $this->assertEquals(1, $test->id);
        $this->assertEquals(1, $test->id_parent);
        $this->assertEquals(2, $test->id_child);
        $this->assertEquals('Larrock\ComponentBlocks\Models\Blocks', $test->model_parent);
        $this->assertEquals('Larrock\ComponentBlocks\Models\Blocks', $test->model_child);
        $this->assertNull($test->cost);

    }

    public function testAddPluginSeo()
    {
        $this->component->addPluginSeo();
        $this->assertInstanceOf(Component::class, $this->component->addPluginSeo());
        $this->assertArrayHasKey('seo', $this->component->plugins_backend);
        $this->assertArrayHasKey('rows', $this->component->plugins_backend['seo']);
        $this->assertCount(3, $this->component->plugins_backend['seo']['rows']);
        $this->assertArrayHasKey('url', $this->component->rows);
    }

    public function testAddPluginImages()
    {
        $this->component->addPluginImages();
        $this->assertInstanceOf(Component::class, $this->component->addPluginImages());
        $this->assertArrayHasKey('images', $this->component->plugins_backend);
    }

    public function testAddCustomMediaConversions()
    {
        $this->component->addCustomMediaConversions(['test']);
        $this->assertEquals([0 => 'test'], $this->component->customMediaConversions);
    }

    public function testAddPluginFiles()
    {
        $this->assertInstanceOf(Component::class, $this->component->addPluginFiles());
        $this->assertArrayHasKey('files', $this->component->addPluginFiles()->plugins_backend);
    }

    public function testAddAnonsToModule()
    {
        $this->component->addAnonsToModule(1);
        $this->assertArrayHasKey('anons_category', $this->component->settings);
        $this->assertEquals(1, $this->component->settings['anons_category']);
        $this->assertArrayHasKey('anons_merge', $this->component->rows);
        $this->assertArrayHasKey('anons_description', $this->component->rows);
        $this->assertArrayHasKey('anons', $this->component->plugins_backend);
    }

    public function testAddDataPlugins()
    {
        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();
        $data = new Config();
        $data->getSeo = DB::connection()->table('seo')->where('id', '=', 1)->first();
        $this->component->addPluginSeo();
        $this->component->addDataPlugins($data);

        $this->assertEquals('test', $this->component->rows['seo_title']->default);
        $this->assertEquals('test', $this->component->rows['seo_description']->default);
        $this->assertEquals('test', $this->component->rows['seo_keywords']->default);
    }

    public function testOverrideComponent()
    {
        $this->component->overrideComponent('name', 'test');
        $this->assertEquals('test', $this->component->name);
    }

    public function testIsSearchable()
    {
        $this->component->isSearchable();
        $this->assertTrue($this->component->searchable);
    }

    public function testRenderAdminMenu()
    {
        $this->assertEquals('', $this->component->renderAdminMenu());
    }

    public function testCreateSitemap()
    {
        $this->assertNull($this->component->createSitemap());
    }

    public function testCreateRss()
    {
        $this->assertNull($this->component->createRSS());
    }

    public function testSearch()
    {
        $this->assertNull($this->component->search(null));
    }
}
