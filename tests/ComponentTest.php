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

    protected function setUpSeoDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('seo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->integer('seo_id_connect')->nullable();
            $table->string('seo_url_connect')->nullable();
            $table->string('seo_type_connect');
            $table->timestamps();

            $table->index(['seo_id_connect', 'seo_url_connect']);
        });

        DB::connection()->table('seo')->insert([
            'seo_title' => 'test',
            'seo_description' => 'test',
            'seo_keywords' => 'test',
            'seo_id_connect' => 1,
            'seo_url_connect' => 'test',
            'seo_type_connect' => 'test',
        ]);
    }

    protected function setUpLinkDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('link', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_parent')->unsigned();
            $table->integer('id_child')->unsigned();
            $table->char('model_parent', 191);
            $table->char('model_child', 191);
            $table->float('cost')->nullable();
            $table->timestamps();
            $table->index(['id_parent', 'model_parent', 'model_child']);
        });
    }

    public function testGetConfig()
    {
        $attributes = ['name', 'title', 'description', 'table', 'rows', 'customMediaConversions', 'model', 'active',
            'plugins_backend', 'plugins_front', 'settings', 'searchable', 'tabs', 'tabs_data', 'valid'];
        foreach ($attributes as $attribute){
            $this->assertClassHasAttribute($attribute, \get_class($this->component));
        }
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
        $this->assertEquals([0 => 'web', 1 => 'GetSeo'], $this->component->combineFrontMiddlewares());
        $this->assertEquals([0 => 'web', 1 => 'GetSeo', 2 => 'Test'], $this->component->combineFrontMiddlewares(['Test']));
    }

    public function testCombineAdminMiddlewares()
    {
        $this->assertEquals([0 => 'web', 1 => 'level:2', 2 => 'LarrockAdminMenu', 3 => 'SaveAdminPluginsData', 4 => 'SiteSearchAdmin'],
            $this->component->combineAdminMiddlewares());
        $this->assertEquals([0 => 'web', 1 => 'level:2', 2 => 'LarrockAdminMenu', 3 => 'SaveAdminPluginsData', 4 => 'SiteSearchAdmin', 5 => 'Test'],
            $this->component->combineAdminMiddlewares(['Test']));
    }

    /**
     * @throws \Exception
     */
    public function testSavePluginsData()
    {
        $this->setUpSeoDatabase();

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

        //TODO:Проверка на внесение данных анонса
        //LarrockFeed не является частью LarrockCore
        /*$request = Request::create('', 'POST', [
            'anons_merge' => 1,
            'anons_description' => 'anons_description_default'
        ]);
        $this->component->savePluginsData($request);*/

        //Проверка на вставку связей
        $this->setUpLinkDatabase();
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
}
