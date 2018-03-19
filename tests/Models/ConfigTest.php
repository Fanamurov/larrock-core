<?php

namespace Larrock\Core\Tests\Models;

use Larrock\Core\Models\Config;
use Orchestra\Testbench\TestCase;

class ConfigTest extends TestCase
{
    public function testGetValueAttribute()
    {
        $Config = new Config();
        $this->assertEquals(['test' => 'value'], $Config->getValueAttribute(serialize(['test' => 'value'])));
    }
}