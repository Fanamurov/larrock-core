<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;
use Larrock\Core\Traits\AdminMethodsStore;
use Larrock\Core\Traits\ShareMethods;
use Orchestra\Testbench\TestCase;
use Proengsoft\JsValidation\JsValidationServiceProvider;

class AdminMethodsStoreTest extends TestCase
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
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class,
            BreadcrumbsServiceProvider::class,
            JsValidationServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs'
        ];
    }

    public function testShareMethods()
    {
        $test = new AdminMethodsStoreMock();
        $this->assertCount(1, $test->shareMethods());
    }

    public function testStore()
    {
        $request = Request::create('/admin/create', 'POST', [
            'title' => 'Новый материал',
            'url' => 'novyy-material',
            'active' => 1
        ]);
        $test = new AdminMethodsStoreMock();
        $load = $test->store($request);
        $this->assertEquals(302, $load->getStatusCode());
        $this->assertNotNull(Blocks::find(2));

        //Проверка внесения данных без редиректа (получение ошибки)
        $test->allow_redirect = null;
        /** @var JsonResponse $load */
        $load = $test->store($request);
        $this->assertInstanceOf(JsonResponse::class, $load);
        $this->assertEquals(200, $load->getStatusCode());
        $response = json_decode($load->getContent());
        $this->assertEquals('danger', $response->status);
        $this->assertEquals('The url has already been taken.', $response->message);

        //Проверка внесения данных без редиректа
        $request = Request::create('/admin/create', 'POST', [
            'title' => 'Новый материал2',
            'url' => 'novyy-material2',
            'active' => 1
        ]);
        $test->allow_redirect = null;
        $load = $test->store($request);
        $this->assertInstanceOf(Blocks::class, $load);
        $this->assertEquals(3, $load->id);
    }
}

class AdminMethodsStoreMock
{
    use AdminMethodsStore, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
    }
}
