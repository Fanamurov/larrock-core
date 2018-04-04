<?php

namespace Larrock\Core\Tests\Traits;

use Larrock\Core\Traits\AdminMethods;
use Orchestra\Testbench\TestCase;

class ShareMethodsTest extends TestCase
{
    public function testShareMethods()
    {
        $test = new TestTrait();
        $this->assertCount(6, $test->shareMethods());
    }
}

class TestTrait
{
    use AdminMethods;
}
