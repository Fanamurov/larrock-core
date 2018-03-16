<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormDate;
use Larrock\Core\LarrockCoreServiceProvider;

class FormDateTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormDate */
    protected $FormDate;

    protected function setUp()
    {
        parent::setUp();

        $this->FormDate = new FormDate('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormDate);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testRender()
    {
        $this->assertNotEmpty($this->FormDate->render($this->FormDate, collect([])));
        $this->assertEquals(date('Y-m-d'), $this->FormDate->default);
    }
}