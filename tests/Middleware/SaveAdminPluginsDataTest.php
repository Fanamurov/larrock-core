<?php

namespace Larrock\Core\Tests\Middleware;

use Illuminate\Http\Request;
use Larrock\Core\Middleware\SaveAdminPluginsData;
use Orchestra\Testbench\TestCase;

class SaveAdminPluginsDataTest extends TestCase
{
    public function testHandle()
    {
        $saveAdminPluginsData= new SaveAdminPluginsData();
        $request = new Request();
        $test = $saveAdminPluginsData->handle($request, function (){
            return 'test';
        });
        $this->assertEquals('test', $test);
    }
}