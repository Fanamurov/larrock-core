<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateFeedDatabase
{
    /** Поднимаем тестовую таблицу конфига */
    public function setUpBlocksDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('feed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('category')->unsigned()->index('feed_category_foreign');
            $table->text('short');
            $table->text('description');
            $table->string('url', 191)->unique();
            $table->dateTime('date');
            $table->integer('position')->default(0);
            $table->integer('active')->default(1);
            $table->integer('user_id')->unsigned()->index('feed_user_id_foreign');
            $table->timestamps();

            $table->index(['url', 'active', 'category']);
        });

        DB::connection()->table('feed')->insert([
            'title' => 'test',
            'description' => 'test',
            'url' => 'test',
            'category' => 1,
            'active' => 1
        ]);
    }
}