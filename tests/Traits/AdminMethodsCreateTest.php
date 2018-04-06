<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Larrock\ComponentCategory\LarrockComponentCategoryServiceProvider;
use Larrock\ComponentFeed\FeedComponent;
use Larrock\ComponentFeed\LarrockComponentFeedServiceProvider;
use Larrock\ComponentFeed\Models\Feed;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateCategoryDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateFeedDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;
use Larrock\Core\Traits\AdminMethodsCreate;
use Larrock\Core\Traits\AdminMethodsStore;
use Larrock\Core\Traits\ShareMethods;
use Orchestra\Testbench\TestCase;
use Proengsoft\JsValidation\JsValidationServiceProvider;

class AdminMethodsCreateTest extends TestCase
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

        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();

        $seed = new CreateFeedDatabase();
        $seed->setUpFeedDatabase();

        $seed = new CreateCategoryDatabase();
        $seed->setUpCategoryDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentFeedServiceProvider::class,
            LarrockComponentCategoryServiceProvider::class,
            BreadcrumbsServiceProvider::class,
            JsValidationServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockFeed' => 'Larrock\ComponentFeed\Facades\LarrockFeed',
            'LarrockCategory' => 'Larrock\ComponentCategory\Facades\LarrockCategory',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs'
        ];
    }

    public function testShareMethods()
    {
        $test = new AdminMethodsCreateMock();
        $this->assertCount(2, $test->shareMethods());
    }

    /**
     * @throws \Exception
     */
    public function testCreate()
    {
        $request = Request::create('/admin/feed/create', 'POST', []);

        $test = new AdminMethodsCreateMock();
        $load = $test->create($request);
        $this->assertEquals(302, $load->getStatusCode());
        $this->assertNotNull(Feed::find(2));

        //Проверка на попытку создания материала с тем же title
        $request = Request::create('/admin/feed/create', 'POST', []);
        /** @var RedirectResponse $load */
        $load = $test->create($request);
        $this->assertArrayHasKey(0, $load->getSession()->get('message.danger'));
        $this->assertEquals('Материал с тарим url уже существует: /admin/feed/2/edit', $load->getSession()->get('message.danger.0'));

        //Проверка на попытку создания материала с несуществующим разделом
        $request = Request::create('/admin/feed/create', 'POST', [
            'title' => 'new_title_feed',
            'category' => 2
        ]);
        /** @var RedirectResponse $load */
        $load = $test->create($request);
        $this->assertEquals(302, $load->getStatusCode());
        $feed = Feed::find(3);
        $this->assertNotNull($feed);
        $this->assertEquals(2, $feed->category);

        //Создание материала с переданным category
        $request = Request::create('/admin/feed/create', 'POST', [
            'title' => 'new_title2',
            'category' => 1,
            'short' => 'short',
            'description' => 'description'
        ]);
        /** @var RedirectResponse $load */
        $load = $test->create($request);
        $this->assertEquals(302, $load->getStatusCode());
        $this->assertNotNull(Feed::find(4));
    }
}

class AdminMethodsCreateMock
{
    use AdminMethodsCreate, AdminMethodsStore, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new FeedComponent();
    }
}
