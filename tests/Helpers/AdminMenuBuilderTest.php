<?php

namespace Larrock\Core\Tests\Helpers;

use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\Core\Helpers\AdminMenuBuilder;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Orchestra\Testbench\TestCase;

class AdminMenuBuilderTest extends TestCase
{
    /** @var AdminMenuBuilder */
    protected $AdminMenuBuilder;

    protected function setUp()
    {
        parent::setUp();

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        $this->AdminMenuBuilder = new AdminMenuBuilder();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->AdminMenuBuilder);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('larrock-core-adminmenu.components', [
            new \Larrock\ComponentBlocks\BlocksComponent()
        ]);
        $app['config']->set('larrock-core-adminmenu.other_items', [
            new \Larrock\ComponentBlocks\BlocksComponent()
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks'
        ];
    }

    public function testTopMenu()
    {
        $test = $this->AdminMenuBuilder->topMenu();
        $this->assertArrayHasKey('menu', $test);
        $this->assertArrayHasKey('menu_other', $test);
        $this->assertObjectHasAttribute('view', $test['menu'][0]);
        $this->assertObjectHasAttribute('view', $test['menu_other'][0]);
    }
}