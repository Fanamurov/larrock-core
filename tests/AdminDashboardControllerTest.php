<?php

namespace Larrock\Core\Tests;

use Larrock\Core\AdminDashboardController;
use Larrock\Core\LarrockCoreServiceProvider;
use Orchestra\Testbench\TestCase;

class AdminDashboardControllerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testIndex()
    {
        $controller = new AdminDashboardController();
        $test = $controller->index();

        $this->assertNotNull($test);
        $this->assertObjectHasAttribute('view', $test);
        $this->assertObjectHasAttribute('data', $test);
    }
}