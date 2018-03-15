<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateConfigDatabase
{
    /** Поднимаем тестовую таблицу конфига */
    public function setUpTestDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('value');
            $table->integer('type');
            $table->timestamps();
        });
        DB::connection()->table('config')->insert([
            'name' => 'name',
            'value' => 'value',
            'type' => 'type',
        ]);
    }
}