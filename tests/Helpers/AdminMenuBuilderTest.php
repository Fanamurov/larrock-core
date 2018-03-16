<?php

namespace Larrock\Core\Tests\Helpers;

use Larrock\Core\Helpers\AdminMenuBuilder;
use Larrock\Core\LarrockCoreServiceProvider;
use Orchestra\Testbench\TestCase;

class AdminMenuBuilderTest extends TestCase
{
    /** @var AdminMenuBuilder */
    protected $AdminMenuBuilder;

    protected function setUp()
    {
        parent::setUp();

        $this->AdminMenuBuilder = new AdminMenuBuilder();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->AdminMenuBuilder);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testTopMenu()
    {
        $this->assertEquals(['menu' => [], 'menu_other' => []], $this->AdminMenuBuilder->topMenu());
    }
}