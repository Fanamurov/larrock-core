<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\LarrockCoreServiceProvider;
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

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSetData()
    {
        $model = new Config();
        $this->FBElement->setData($model);
        $this->assertInstanceOf(Config::class, $this->FBElement->data);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetFBTemplate()
    {
        $this->FBElement->setFBTemplate('template');
        $this->assertEquals('template', $this->FBElement->FBTemplate);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function test__toString()
    {
        $this->assertNotNull($this->FBElement->__toString());
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
        $this->assertTrue($this->FBElement->inTableAdmin);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetInTableAdminAjaxEditable()
    {
        $this->FBElement->setInTableAdminEditable();
        $this->assertTrue($this->FBElement->inTableAdminEditable);
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
        $this->assertEquals('uk-width-1-1 test', $this->FBElement->cssClassGroup);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetCssClass()
    {
        $this->FBElement->setCssClass('test');
        $this->assertEquals('uk-width-1-1 test', $this->FBElement->cssClass);
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
        $this->assertEquals('test', $this->FBElement->templateAdmin);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }

    public function testSetFillable()
    {
        $this->FBElement->setFillable();
        $this->assertTrue($this->FBElement->fillable);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FBElement);
    }
}