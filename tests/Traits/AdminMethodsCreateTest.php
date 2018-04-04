<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Http\Request;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
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
        $test = new AdminMethodsCreateMock();
        $this->assertCount(2, $test->shareMethods());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage AdminMethodsStore not found in this Controller
     * @throws \Exception
     */
    public function testCreate()
    {
        $request = new Request();
        $test = new AdminMethodsCreateMock();
        $load = $test->create($request);
        $this->assertEquals(302, $load->getStatusCode());
        $this->assertNotNull(Blocks::find(2));

        $test = new class { use AdminMethodsCreate; };
        $test->create($request);
    }
}

class AdminMethodsCreateMock
{
    use AdminMethodsCreate, AdminMethodsStore, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
    }
}
