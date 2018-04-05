<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentCategory\LarrockComponentCategoryServiceProvider;
use Larrock\ComponentFeed\FeedComponent;
use Larrock\ComponentFeed\LarrockComponentFeedServiceProvider;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateCategoryDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateFeedDatabase;
use Larrock\Core\Traits\AdminMethodsIndex;
use Larrock\Core\Traits\ShareMethods;
use Orchestra\Testbench\TestCase;

class AdminMethodsIndexTest extends TestCase
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

        $seed = new CreateCategoryDatabase();
        $seed->setUpCategoryDatabase();

        $seed = new CreateFeedDatabase();
        $seed->setUpFeedDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class,
            BreadcrumbsServiceProvider::class,
            LarrockComponentFeedServiceProvider::class,
            LarrockComponentCategoryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks',
            'LarrockFeed' => 'Larrock\ComponentFeed\Facades\LarrockFeed',
            'LarrockCategory' => 'Larrock\ComponentCategory\Facades\LarrockCategory',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs'
        ];
    }

    public function testShareMethods()
    {
        $test = new AdminMethodsIndexMock();
        $this->assertCount(1, $test->shareMethods());
    }

    public function testIndex()
    {
        $test = new AdminMethodsIndexMock();
        $this->assertEquals('larrock::admin.admin-builder.categories', $test->index()->getName());
        $this->assertEquals(1, $test->index()->getData()['categories']->total());

        $test = new AdminMethodsIndexMockBlock();
        $this->assertEquals('larrock::admin.admin-builder.index', $test->index()->getName());
        $this->assertEquals(1, $test->index()->getData()['data']->total());

        $test = new AdminMethodsIndexMockBlockNotPosition();
        $this->assertEquals('larrock::admin.admin-builder.index', $test->index()->getName());
        $this->assertEquals(1, $test->index()->getData()['data']->total());
    }
}

class AdminMethodsIndexMock
{
    use AdminMethodsIndex, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new FeedComponent();
    }
}

class AdminMethodsIndexMockBlock
{
    use AdminMethodsIndex, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
    }
}

class AdminMethodsIndexMockBlockNotPosition
{
    use AdminMethodsIndex, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
        $this->config->removeRow('position');
    }
}
