<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Helpers\FormBuilder\FBElement;

class FormTextareaTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormTextarea */
    protected $FormTextarea;

    protected function setUp()
    {
        parent::setUp();

        $this->FormTextarea = new FormTextarea('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormTextarea);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSetTypo()
    {
        $this->FormTextarea->setTypo();
        $this->assertTrue($this->FormTextarea->typo);
    }

    public function testSetNotEditor()
    {
        $this->FormTextarea->setNotEditor();
        $this->assertEquals('uk-width-1-1 not-editor', $this->FormTextarea->cssClass);
        $this->assertInstanceOf(FBElement::class, $this->FormTextarea);
        $this->assertInstanceOf(FormTextarea::class, $this->FormTextarea);
    }

    public function testRender()
    {
        $this->FormTextarea->setDefaultValue('test');
        $this->assertNotEmpty($this->FormTextarea->render($this->FormTextarea, collect([])));
    }
}