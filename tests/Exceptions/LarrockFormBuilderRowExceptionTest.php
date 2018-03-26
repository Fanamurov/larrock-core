<?php

namespace Larrock\Core\Tests;

use Larrock\Core\Helpers\FormBuilder\FormTags;
use Larrock\Core\LarrockCoreServiceProvider;

class LarrockFormBuilderRowExceptionTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testWithMessage()
    {
        $row = new FormTags('test', 'test');
        $this->assertEquals('modelParent или modelChild поля test не задан', $row);
    }
}