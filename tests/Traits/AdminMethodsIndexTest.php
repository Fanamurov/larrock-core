<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
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
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class,
            BreadcrumbsServiceProvider::class,
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
        $test = new AdminMethodsIndexMock();
        $this->assertCount(1, $test->shareMethods());
    }

    public function testIndex()
    {
        $test = new AdminMethodsIndexMock();
        $this->assertEquals(1, $test->index()->getData()['data']->total());
    }
}

class AdminMethodsIndexMock
{
    use AdminMethodsIndex, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
    }
}
