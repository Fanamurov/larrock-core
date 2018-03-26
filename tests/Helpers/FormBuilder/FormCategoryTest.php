<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Helpers\FormBuilder\FormCategory;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Models\Config;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;

class FormCategoryTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormCategory */
    protected $FormCategory;

    protected function setUp()
    {
        parent::setUp();

        $this->FormCategory = new FormCategory('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormCategory);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    public function testSetMaxItems()
    {
        $this->FormCategory->setMaxItems(1);
        $this->assertEquals(1, $this->FormCategory->max_items);
        $this->assertInstanceOf(FBElement::class, $this->FormCategory);
        $this->assertInstanceOf(FormCategory::class, $this->FormCategory);
    }

    public function setAllowEmpty()
    {
        $this->FormCategory->setAllowEmpty();
        $this->assertTrue($this->FormCategory->allow_empty);
        $this->assertInstanceOf(FBElement::class, $this->FormCategory);
        $this->assertInstanceOf(FormCategory::class, $this->FormCategory);
    }

    public function testSetConnect()
    {
        $this->FormCategory->setConnect(Config::class, 'test_relation', 'test_group');
        $this->assertEquals(Config::class, $this->FormCategory->connect->model);
        $this->assertEquals('test_relation', $this->FormCategory->connect->relation_name);
        $this->assertEquals('test_group', $this->FormCategory->connect->group_by);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FormCategory);
    }

    /**
     * @expectedException \Larrock\Core\Exceptions\LarrockFormBuilderRowException
     * @expectedExceptionMessage У поля test_name сначала нужно определить setConnect
     */
    public function testSetWhereConnect()
    {
        $this->FormCategory->setConnect(Config::class, 'test_relation', 'test_group');
        $this->FormCategory->setWhereConnect('test_key', 'test_value');
        $this->assertEquals('test_key', $this->FormCategory->connect->where_key);
        $this->assertEquals('test_value', $this->FormCategory->connect->where_value);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FormCategory);

        $this->FormCategory = new FormCategory('test_name', 'test_title');
        $this->FormCategory->setWhereConnect('test_key', 'test_value');
    }

    public function test__toString()
    {
        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        $this->FormCategory->setConnect(Config::class, 'test_relation');
        $data = new Config();
        $data->test_relation = collect([]);
        $this->FormCategory->setDefaultValue('test');

        $this->assertNotEmpty($this->FormCategory->__toString());
    }
}