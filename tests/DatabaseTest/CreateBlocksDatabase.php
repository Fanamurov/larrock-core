<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateBlocksDatabase
{
    /** Поднимаем тестовую таблицу конфига */
    public function setUpBlocksDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url', 191)->unique();
            $table->integer('position')->default(0);
            $table->string('redirect')->default('');
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->index(['url', 'active']);
        });

        DB::connection()->table('blocks')->insert([
            'title' => 'test',
            'description' => 'test',
            'url' => 'test',
        ]);
    }
}