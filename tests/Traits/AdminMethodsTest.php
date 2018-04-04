<?php

namespace Larrock\Core\Tests\Traits;

use Larrock\Core\Traits\AdminMethods;
use Orchestra\Testbench\TestCase;

class AdminMethodsTest extends TestCase
{
    public function testShareMethods()
    {
        $test = new class { use AdminMethods; };
        $this->assertCount(6, $test->shareMethods());
    }
}