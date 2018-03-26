<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormButton;
use Larrock\Core\LarrockCoreServiceProvider;

class FormButtonTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormButton */
    protected $FormButton;

    protected function setUp()
    {
        parent::setUp();

        $this->FormButton = new FormButton('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormButton);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSetTypo()
    {
        $this->FormButton->setButtonType('button');
        $this->assertEquals('button', $this->FormButton->buttonType);
    }
}