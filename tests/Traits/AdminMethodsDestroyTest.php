<?php

namespace Larrock\Core\Tests\Traits;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Http\Request;
use Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;
use Larrock\Core\Traits\AdminMethodsDestroy;
use Larrock\Core\Traits\ShareMethods;
use Orchestra\Testbench\TestCase;
use Proengsoft\JsValidation\JsValidationServiceProvider;

class AdminMethodsDestroyTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('medialibrary.media_model', \Spatie\MediaLibrary\Models\Media::class);
    }

    protected function setUp()
    {
        parent::setUp();

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();

        $seed = new CreateMediaDatabase();
        $seed->setUpMediaDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class,
            BreadcrumbsServiceProvider::class,
            JsValidationServiceProvider::class,
            LarrockComponentAdminSeoServiceProvider::class
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
        $test = new AdminMethodsDestroyMock();
        $this->assertCount(1, $test->shareMethods());
    }

    public function testDestroy()
    {
        $request = new Request();
        $test = new AdminMethodsDestroyMock();
        $load = $test->destroy($request, 1);
        $this->assertEquals(302, $load->getStatusCode());
        $this->assertNull(Blocks::find(1));
    }
}

class AdminMethodsDestroyMock
{
    use AdminMethodsDestroy, ShareMethods;

    protected $config;

    public function __construct()
    {
        $this->config = new BlocksComponent();
    }
}
