<?php

namespace Larrock\Core\Tests\Models;

use Larrock\Core\Models\Config;
use Larrock\Core\Models\Link;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateLinkDatabase;
use Orchestra\Testbench\TestCase;

class ConfigLinkTest extends TestCase
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

        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        $seed = new CreateLinkDatabase();
        $seed->setUpLinkDatabase();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testGetFullDataChild()
    {
        $config = new Link();
        $config->model_child = Config::class;
        $config->id_child = 1;
        $test = $config->getFullDataChild();
        $this->assertInstanceOf(Config::class, $test);
        $this->assertEquals(1, $test->id);
        $this->assertEquals('name', $test->name);
        $this->assertEquals([0 => 'value'], $test->value);
        $this->assertEquals('type', $test->type);
    }
}