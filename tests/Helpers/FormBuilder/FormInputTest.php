<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\LarrockCoreServiceProvider;

class FormInputTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormInput */
    protected $FormInput;

    protected function setUp()
    {
        parent::setUp();

        $this->FormInput = new FormInput('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormInput);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSetTypo()
    {
        $this->FormInput->setTypo();
        $this->assertTrue($this->FormInput->typo);
    }

    public function testRender()
    {
        $this->assertNotEmpty($this->FormInput);
    }
}