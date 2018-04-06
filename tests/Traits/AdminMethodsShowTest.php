<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Larrock\ComponentCategory\LarrockComponentCategoryServiceProvider;
use Larrock\ComponentFeed\FeedComponent;
use Larrock\ComponentFeed\LarrockComponentFeedServiceProvider;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateCategoryDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateFeedDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
use Larrock\Core\Traits\AdminMethodsShow;
use Larrock\Core\Traits\ShareMethods;
use Orchestra\Testbench\TestCase;
use Proengsoft\JsValidation\JsValidationServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class AdminMethodsShowTest extends TestCase
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

        $seed = new CreateFeedDatabase();
        $seed->setUpFeedDatabase();

        $seed = new CreateCategoryDatabase();
        $seed->setUpCategoryDatabase();

        $seed = new CreateMediaDatabase();
        $seed->setUpMediaDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentCategoryServiceProvider::class,
            LarrockComponentFeedServiceProvider::class,
            BreadcrumbsServiceProvider::class,
            JsValidationServiceProvider::class,
            MediaLibraryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockCategory' => 'Larrock\ComponentCategory\Facades\LarrockCategory',
            'LarrockFeed' => 'Larrock\ComponentFeed\Facades\LarrockFeed',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs'
        ];
    }

    public function testShareMethods()
    {
        $test = new AdminMethodsShowMock();
        $this->assertCount(1, $test->shareMethods());
    }

    public function testShow()
    {
        $test = new AdminMethodsShowMock();
        /** @var View $load */
        $load = $test->show(1);
        $this->assertEquals('larrock::admin.admin-builder.categories', $load->getName());
        $this->assertNotEmpty($load->render());
    }
}

class AdminMethodsShowMock
{
    use AdminMethodsShow, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new FeedComponent();
        $this->config->shareConfig();
    }
}
