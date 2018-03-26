<?php

namespace Larrock\Core\Tests;

use Larrock\Core\Helpers\FormBuilder\FormCategory;
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
        $this->assertEquals('Отрисовка не возможна! modelParent или modelChild поля test не задан', (string)$row);

        $row = new FormCategory('test', 'test');
        $this->assertEquals('Отрисовка не возможна! Поля model, relation_name не установлены через setConnect()', (string)$row);
    }
}