<?php

namespace Larrock\Core\Tests;

use Larrock\Core\LarrockCoreServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
        ];
    }
}
