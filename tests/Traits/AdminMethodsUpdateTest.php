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
use Larrock\Core\Traits\AdminMethodsUpdate;
use Larrock\Core\Traits\ShareMethods;
use Orchestra\Testbench\TestCase;
use Proengsoft\JsValidation\JsValidationServiceProvider;

class AdminMethodsUpdateTest extends TestCase
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
        $test = new AdminMethodsUpdateMock();
        $this->assertCount(1, $test->shareMethods());
    }

    public function testUpdate()
    {
        $request = Request::create('/admin/update', 'POST', [
            'title' => 'Новый заголовок',
            'url' => 'test',
            'active' => 1
        ]);
        $test = new AdminMethodsUpdateMock();
        $load = $test->update($request, 1);
        $this->assertEquals(302, $load->getStatusCode());
        $this->assertEquals('Новый заголовок', Blocks::find(1)->title);
    }
}

class AdminMethodsUpdateMock
{
    use AdminMethodsUpdate, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
    }
}
