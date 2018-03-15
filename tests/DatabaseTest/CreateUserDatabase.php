<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use DB;

class CreateUserDatabase
{
    /** Поднимаем таблицы пользователей и ролей */
    public function setUpUserDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('fio');
            $table->rememberToken();
            $table->timestamps();
            $table->index(['email']);
        });
        DB::connection()->getSchemaBuilder()->create('roles', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug', 191)->unique();
            $table->string('description')->nullable();
            $table->integer('level')->default(1);
            $table->timestamps();
        });
        DB::connection()->getSchemaBuilder()->create('role_user', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
        DB::connection()->table('roles')->insert([
            'name' => 'Admin',
            'slug' => 'Админ',
            'description' => NULL,
            'level' => 3
        ]);
        DB::connection()->table('roles')->insert([
            'name' => 'Moderator',
            'slug' => 'Модератор',
            'description' => NULL,
            'level' => 2
        ]);
        DB::connection()->table('roles')->insert([
            'name' => 'User',
            'slug' => 'Пользователь',
            'description' => NULL,
            'level' => 1
        ]);
        DB::connection()->table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@larrock-cms.ru',
            'password' => bcrypt('password'),
            'first_name' => 'Admin',
            'last_name' => 'Khabarovsk',
            'fio' => 'Admin Khabarovsk'
        ]);
    }
}