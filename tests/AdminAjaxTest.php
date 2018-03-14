<?php

namespace Larrock\Core\Tests;

use Larrock\Core\AdminAjax;
use Tests\TestCase;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Schema\Blueprint;

class AdminAjaxTest extends TestCase
{
    /** @var AdminAjax */
    protected $controller;

    /** @var string */
    protected $test_table;

    protected function setUp()
    {
        parent::setUp();

        $this->createApplication();
        $this->controller = new AdminAjax();
        $this->test_table = 'test_model';

        \config()->set('database.default', 'sqlite');
        \config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $this->setUpUserDatabase();
        $this->setUpTestDatabase();
        $this->setUpMediaDatabase();
    }

    public function tearDown() {
        unset($this->controller, $this->test_table);
    }

    /** Поднимаем тестовую таблицу */
    protected function setUpTestDatabase()
    {
        DB::connection()->getSchemaBuilder()->create($this->test_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('value');
            $table->integer('type');
            $table->timestamps();
        });
        DB::connection()->table($this->test_table)->insert([
            'name' => 'name',
            'value' => 'value',
            'type' => 'type',
        ]);
    }

    /** Поднимаем таблицы пользователей и ролей */
    protected function setUpUserDatabase()
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

    protected function setUpMediaDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->unsignedInteger('order_column')->nullable();
            $table->nullableTimestamps();
        });

        DB::connection()->table('media')->insert([
            'model_id' => 1,
            'model_type' => $this->test_table,
            'collection_name' => 'images',
            'name' => 'test',
            'file_name' => 'test.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 'media',
            'size' => 1000,
            'manipulations' => '[]',
            'custom_properties' => '{"alt": "photo", "gallery": "gelievye-shary"}',
            'order_column' => 1
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @throws \Exception
     */
    public function testEditRow()
    {
        $request = Request::create('/admin/ajax/EditRow', 'POST', [
            'value_where' => 1,
            'row_where' => 'id',
            'value' => 'updated_row',
            'row' => 'value',
            'table' => $this->test_table
        ]);

        $data = $this->controller->EditRow($request);
        $content = json_decode($data->getContent());

        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals(trans('larrock::apps.row.update', ['name' => 'value']), $content->message);

        $data = $this->controller->EditRow($request);
        $content = json_decode($data->getContent());

        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('blank', $content->status);
        $this->assertEquals(trans('larrock::apps.row.blank', ['name' => 'value']), $content->message);
    }

    public function testClearCache()
    {
        $data = $this->controller->ClearCache();
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals(trans('larrock::apps.cache.clear'), $content->message);
    }

    public function testCustomProperties()
    {
        $request = Request::create('/admin/ajax/CustomProperties', 'POST', [
            'id' => 1,
            'position' => 10,
            'alt' => 'alt',
            'gallery' => 'gallery'
        ]);

        $data = $this->controller->CustomProperties($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals(trans('larrock::apps.data.update', ['name' => 'параметров']), $content->message);
    }

    /*public function testGetUploadedMedia()
    {
        $request = Request::create('/admin/ajax/GetUploadedMedia', 'POST', [
            'type' => 'images',
            'model_id' => 1,
            'model_type' => 'test_model'
        ]);

        $data = $this->controller->GetUploadedMedia($request);
        $this->assertEquals(200, $data->getStatusCode());
        //$this->assertEquals('success', $data->status);
    }*/

    /**
     * @throws \Exception
     */
    public function testTranslit()
    {
        $request = Request::create('/admin/ajax/Translit', 'POST', [
            'text' => 'тест значения'
        ]);

        $data = $this->controller->Translit($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals('test-znacheniya', $content->message);
    }

    /**
     * @throws \Exception
     */
    public function testTypograph()
    {
        $request = Request::create('/admin/ajax/Typograph', 'POST', [
            'text' => 'тест значения'
        ]);

        $data = $this->controller->Typograph($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('<p>тест значения</p>', $content->text);
    }

    /**
     * @throws \Exception
     */
    public function testTypographLight()
    {
        $request = Request::create('/admin/ajax/TypographLight', 'POST', [
            'text' => 'тест значения',
            'to_json' => true
        ]);

        $data = $this->controller->TypographLight($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('тест значения', $content->text);
    }
}
