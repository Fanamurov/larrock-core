<?php

namespace Larrock\Core\Tests;

use Config;
use Larrock\Core\LarrockCoreServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use View;

abstract class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
        ];
    }
}
