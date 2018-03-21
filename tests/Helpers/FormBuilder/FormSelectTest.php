<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormSelect;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Helpers\FormBuilder\FBElement;

class FormSelectTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormSelect */
    protected $FormSelect;

    protected function setUp()
    {
        parent::setUp();

        $this->FormSelect = new FormSelect('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormSelect);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSetOptions()
    {
        $this->FormSelect->setOptions(['test']);
        $this->assertEquals(['test'], $this->FormSelect->options);
        $this->assertInstanceOf(FBElement::class, $this->FormSelect);
        $this->assertInstanceOf(FormSelect::class, $this->FormSelect);
    }

    public function testSetOptionsTitle()
    {
        $this->FormSelect->setOptionsTitle('test');
        $this->assertEquals('test', $this->FormSelect->option_title);
        $this->assertInstanceOf(FBElement::class, $this->FormSelect);
        $this->assertInstanceOf(FormSelect::class, $this->FormSelect);
    }

    public function testSetAllowCreate()
    {
        $this->FormSelect->setAllowCreate();
        $this->assertTrue($this->FormSelect->allowCreate);
        $this->assertInstanceOf(FBElement::class, $this->FormSelect);
        $this->assertInstanceOf(FormSelect::class, $this->FormSelect);
    }

    public function testSetConnect()
    {
        $this->FormSelect->setConnect(Config::class, 'test_relation', 'test_group');
        $this->assertEquals(Config::class, $this->FormSelect->connect->model);
        $this->assertEquals('test_relation', $this->FormSelect->connect->relation_name);
        $this->assertEquals('test_group', $this->FormSelect->connect->group_by);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FormSelect);
    }

    /**
     * @expectedException \Larrock\Core\Exceptions\LarrockFormBuilderRowException
     * @expectedExceptionMessage У поля test_name сначала нужно определить setConnect
     */
    public function testSetWhereConnect()
    {
        $this->FormSelect->setConnect(Config::class, 'test_relation', 'test_group');
        $this->FormSelect->setWhereConnect('test_key', 'test_value');
        $this->assertEquals('test_key', $this->FormSelect->connect->where_key);
        $this->assertEquals('test_value', $this->FormSelect->connect->where_value);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FormSelect);

        $this->FormSelect = new FormSelect('test_name', 'test_title');
        $this->FormSelect->setWhereConnect('test_key', 'test_value');
    }

    public function testRender()
    {
        $this->FormSelect->setDefaultValue('test');
        $this->assertNotEmpty($this->FormSelect->render($this->FormSelect, collect([])));
    }
}