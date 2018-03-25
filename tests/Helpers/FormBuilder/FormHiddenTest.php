<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormHidden;
use Larrock\Core\LarrockCoreServiceProvider;

class FormHiddenTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormHidden */
    protected $FormHidden;

    protected function setUp()
    {
        parent::setUp();

        $this->FormHidden = new FormHidden('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormHidden);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testRender()
    {
        $this->FormHidden->setDefaultValue('test');
        $this->assertNotEmpty($this->FormHidden);
    }
}