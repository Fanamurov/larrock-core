<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Models\Config;

class FBElementTest extends \Orchestra\Testbench\TestCase
{
    /** @var FBElement */
    protected $FBElement;

    protected function setUp()
    {
        parent::setUp();

        $this->FBElement = new FBElement('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FBElement);
    }

    public function testSetDefaultValue()
    {
        $this->FBElement->setDefaultValue('test');
        $this->assertEquals('test', $this->FBElement->default);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetInTableAdmin()
    {
        $this->FBElement->setInTableAdmin();
        $this->assertTrue($this->FBElement->in_table_admin);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetInTableAdminAjaxEditable()
    {
        $this->FBElement->setInTableAdminAjaxEditable();
        $this->assertTrue($this->FBElement->in_table_admin_ajax_editable);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetValid()
    {
        $this->FBElement->setValid('required');
        $this->assertEquals('required', $this->FBElement->valid);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);

        $this->FBElement->setValid('test');
        $this->assertEquals('required|test', $this->FBElement->valid);
    }

    public function testIsRequired()
    {
        $this->FBElement->isRequired();
        $this->assertEquals('required', $this->FBElement->valid);
    }

    public function testSetCssClassGroup()
    {
        $this->FBElement->setCssClassGroup('test');
        $this->assertEquals('uk-width-1-1 test', $this->FBElement->css_class_group);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetCssClass()
    {
        $this->FBElement->setCssClass('test');
        $this->assertEquals('uk-width-1-1 test', $this->FBElement->css_class);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetTab()
    {
        $this->FBElement->setTab('test_name', 'test_title');
        $this->assertEquals(['test_name' => 'test_title'], $this->FBElement->tab);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetHelp()
    {
        $this->FBElement->setHelp('test');
        $this->assertEquals('test', $this->FBElement->help);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetConnect()
    {
        $this->FBElement->setConnect(Config::class, 'test_relation', 'test_group');
        $this->assertEquals(Config::class, $this->FBElement->connect->model);
        $this->assertEquals('test_relation', $this->FBElement->connect->relation_name);
        $this->assertEquals('test_group', $this->FBElement->connect->group_by);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    /**
     * @expectedException \Larrock\Core\Exceptions\LarrockFormBuilderRowException
     * @expectedExceptionMessage У поля test_name сначала нужно определить setConnect
     */
    public function testSetWhereConnect()
    {
        $this->FBElement->setWhereConnect('test_key', 'test_value');

        $this->FBElement->setConnect(Config::class, 'test_relation', 'test_group');
        $this->FBElement->setWhereConnect('test_key', 'test_value');
        $this->assertEquals('test_key', $this->FBElement->connect->where_key);
        $this->assertEquals('test_value', $this->FBElement->connect->where_value);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetAttached()
    {
        $this->FBElement->setAttached();
        $this->assertTrue($this->FBElement->attached);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetFiltered()
    {
        $this->FBElement->setFiltered();
        $this->assertTrue($this->FBElement->filtered);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetSorted()
    {
        $this->FBElement->setSorted();
        $this->assertTrue($this->FBElement->sorted);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetTemplate()
    {
        $this->FBElement->setTemplate('test');
        $this->assertEquals('test', $this->FBElement->template);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetTemplateAdmin()
    {
        $this->FBElement->setTemplateAdmin('test');
        $this->assertEquals('test', $this->FBElement->template_admin);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetFillable()
    {
        $this->FBElement->setFillable();
        $this->assertTrue($this->FBElement->fillable);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }
}