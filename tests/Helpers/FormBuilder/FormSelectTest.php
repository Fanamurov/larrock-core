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

    public function testRender()
    {
        $this->FormSelect->setDefaultValue('test');
        $this->assertNotEmpty($this->FormSelect->render($this->FormSelect, collect([])));
    }
}