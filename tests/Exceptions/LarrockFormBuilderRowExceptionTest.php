<?php

namespace Larrock\Core\Tests;

use Larrock\Core\Exceptions\LarrockFormBuilderRowException;

class LarrockFormBuilderRowExceptionTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @expectedException LarrockFormBuilderRowException
     * @expectedExceptionMessage message
     */
    public function testWithMessage()
    {
        $this->expectException('Exception');
        throw new LarrockFormBuilderRowException('message');

    }
}