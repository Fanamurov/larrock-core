<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateFeedDatabase
{
    /** Поднимаем тестовую таблицу конфига */
    public function setUpFeedDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('feed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('category')->unsigned()->index('feed_category_foreign');
            $table->text('short')->nullable();
            $table->text('description')->nullable();
            $table->string('url', 191)->unique();
            $table->dateTime('date');
            $table->integer('position')->default(0);
            $table->integer('active')->default(1);
            $table->integer('user_id')->unsigned()->index('feed_user_id_foreign')->nullable();
            $table->timestamps();

            $table->index(['url', 'active', 'category']);
        });

        DB::connection()->table('feed')->insert([
            'title' => 'test',
            'short' => 'test_s',
            'description' => 'test_d',
            'url' => 'test',
            'category' => 1,
            'active' => 1,
            'date' => date('Y-m-d H:i:s'),
            'user_id' => 1
        ]);
    }
}