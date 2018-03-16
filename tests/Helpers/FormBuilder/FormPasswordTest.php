<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormPassword;
use Larrock\Core\LarrockCoreServiceProvider;

class FormPasswordTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormPassword */
    protected $FormPassword;

    protected function setUp()
    {
        parent::setUp();

        $this->FormPassword = new FormPassword('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormPassword);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testRender()
    {
        $this->assertNotEmpty($this->FormPassword->render($this->FormPassword, collect([])));
    }
}