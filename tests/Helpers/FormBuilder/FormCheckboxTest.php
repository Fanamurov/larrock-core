<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;
use Larrock\Core\LarrockCoreServiceProvider;

class FormCheckboxTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormCheckbox */
    protected $FormCheckbox;

    protected function setUp()
    {
        parent::setUp();

        $this->FormCheckbox = new FormCheckbox('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormCheckbox);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    public function testRender()
    {
        $this->FormCheckbox->setDefaultValue('test');
        $this->assertNotEmpty($this->FormCheckbox);
    }
}