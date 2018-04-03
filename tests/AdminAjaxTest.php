<?php

namespace Larrock\Core\Tests;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Http\UploadedFile;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use \Larrock\Core\AdminAjax;
use Illuminate\Http\Request;
use DB;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateUserDatabase;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\MediaLibrary\Models\Media;

class AdminAjaxTest extends \Orchestra\Testbench\TestCase
{
    /** @var AdminAjax */
    protected $controller;

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('medialibrary.media_model', \Spatie\MediaLibrary\Models\Media::class);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->controller = new AdminAjax();

        $seed = new CreateUserDatabase();
        $seed->setUpUserDatabase();

        $seed = new CreateMediaDatabase();
        $seed->setUpMediaDatabase();

        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            BreadcrumbsServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class,
            MediaLibraryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs',
        ];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->controller);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @throws \Exception
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Не все необходимые поля переданы
     */
    public function testEditRow()
    {
        $request = Request::create('/admin/ajax/EditRow', 'POST', [
            'value_where' => 1,
            'row_where' => 'id',
            'value' => 'updated_row',
            'row' => 'value',
            'table' => 'config'
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

        //Проверка ответа когда изменяемого материала не существует
        $request = Request::create('/admin/ajax/EditRow', 'POST', [
            'value_where' => 100,
            'row_where' => 'id',
            'value' => 'updated_row2',
            'row' => 'value2',
            'table' => 'config'
        ]);
        $data = $this->controller->EditRow($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('error', $content->status);
        $this->assertEquals(trans('larrock::apps.404', ['name' => 'value']) .' '. trans('larrock::apps.data.error'), $content->message);

        //Проверка exception, когда не все необходимые поля переданы
        $request = Request::create('/admin/ajax/EditRow', 'POST', [
            'value_where' => 1,
            'row_where' => 'id',
            'value' => 'updated_row',
            'row' => 'value'
        ]);
        $data = $this->controller->EditRow($request);
        $this->assertEquals(400, $data->getStatusCode());
    }

    public function testClearCache()
    {
        $data = $this->controller->ClearCache();
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals(trans('larrock::apps.cache.clear'), $content->message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Не все необходимые поля переданы
     * @throws \Exception
     */
    public function testUploadFile()
    {
        $request = Request::create('/admin/ajax/UploadFile', 'POST', [
            'model_type' => 'Larrock\ComponentBlocks\Models\Blocks',
            'model_id' => 1,
            'gallery' => 'test_gallery',
        ], [], [
            'files' => UploadedFile::fake()->create('test.txt', 100)
        ]);
        $data = $this->controller->UploadFile($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals('Файл test.txt успешно загружен', $content->message);

        $media = Media::find(1);
        $this->assertEquals('test.jpg', $media->file_name);

        //Обработка исключения
        $request = Request::create('/admin/ajax/UploadFile', 'POST', []);
        $this->controller->UploadFile($request);
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

        //Обработка исключения когда не все поля переданы
        $request = Request::create('/admin/ajax/CustomProperties', 'POST', [
            'position' => 10,
            'alt' => 'alt',
            'gallery' => 'gallery'
        ]);
        $data = $this->controller->CustomProperties($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(400, $data->getStatusCode());
        $this->assertEquals('error', $content->status);
        $this->assertEquals(trans('larrock::apps.param.404', ['name' => 'id']), $content->message);

        //Обработка случая когда обновление не должно пройти
        $request = Request::create('/admin/ajax/CustomProperties', 'POST', [
            'id' => 100,
            'position' => 10,
            'alt' => 'alt',
            'gallery' => 'gallery'
        ]);
        $data = $this->controller->CustomProperties($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(400, $data->getStatusCode());
        $this->assertEquals('error', $content->status);
        $this->assertEquals(trans('larrock::apps.row.error'), $content->message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Не все необходимые поля переданы
     * @throws \Exception
     * @throws \Throwable
     */
    public function testGetUploadedMedia()
    {
        $request = Request::create('/admin/ajax/GetUploadedMedia', 'POST', [
            'type' => 'images',
            'model_id' => 1,
            'model_type' => 'Larrock\ComponentBlocks\Models\Blocks',
        ]);
        $data = $this->controller->GetUploadedMedia($request);
        $this->assertNotEmpty($data->render());

        $request = Request::create('/admin/ajax/GetUploadedMedia', 'POST', [
            'type' => 'files',
            'model_id' => 1,
            'model_type' => 'Larrock\ComponentBlocks\Models\Blocks',
        ]);
        $data = $this->controller->GetUploadedMedia($request);
        $this->assertNotEmpty($data->render());

        //Обработка исключения
        $request = Request::create('/admin/ajax/GetUploadedMedia', 'POST', []);
        $this->controller->GetUploadedMedia($request);
    }

    /**
     * @expectedException \Spatie\MediaLibrary\Exceptions\MediaCannotBeDeleted
     * @expectedExceptionMessage Media with id `1` cannot be deleted because it does not exist or does not belong to model Larrock\ComponentBlocks\Models\Blocks with id 1
     * @throws \Exception
     */
    public function testDeleteUploadedMedia()
    {
        //Выполняем запрос на удаление
        $request = Request::create('/admin/ajax/DeleteUploadedMedia', 'POST', [
            'model' => 'Larrock\ComponentBlocks\Models\Blocks',
            'model_id' => 1,
            'id' => 1
        ]);
        $this->controller->DeleteUploadedMedia($request);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Не все необходимые поля переданы
     * @throws \Exception
     */
    public function testDeleteUploadedMediaTwo()
    {
        //Обработка исключения
        $request = Request::create('/admin/ajax/DeleteUploadedMedia', 'POST', []);
        $this->controller->DeleteUploadedMedia($request);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Не все необходимые поля переданы
     * @throws \Exception
     */
    public function testDeleteAllUploadedMediaByType()
    {
        $request = Request::create('/admin/ajax/DeleteAllUploadedMediaByType', 'POST', [
            'model' => 'Larrock\ComponentBlocks\Models\Blocks',
            'type' => 'images',
            'model_id' => 1
        ]);
        $data = $this->controller->DeleteAllUploadedMediaByType($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals(trans('larrock::apps.delete.files'), $content->message);

        //Обработка исключения
        $request = Request::create('/admin/ajax/DeleteAllUploadedMediaByType', 'POST', []);
        $this->controller->DeleteAllUploadedMediaByType($request);
    }

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

        $request = Request::create('/admin/ajax/Translit', 'POST', [
            'text' => 'тест-значения',
            'table' => 'Larrock\ComponentBlocks\Models\Blocks'
        ]);

        $data = $this->controller->Translit($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertEquals('test_znacheniya', $content->message);

        $request = Request::create('/admin/ajax/Translit', 'POST', [
            'text' => 'test',
            'table' => 'Larrock\ComponentBlocks\Models\Blocks'
        ]);

        $data = $this->controller->Translit($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('success', $content->status);
        $this->assertNotEquals('test', $content->message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Не все необходимые поля переданы [text]
     * @throws \Exception
     */
    public function testTypographLight()
    {
        $request = Request::create('/admin/ajax/TypographLight', 'POST', [
            'text' => 'тест значения'
        ]);

        $data = $this->controller->TypographLight($request);
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('тест значения', $data->getData()->text);

        //json
        $request = Request::create('/admin/ajax/TypographLight', 'POST', [
            'text' => 'тест значения',
            'to_json' => true
        ]);

        $data = $this->controller->TypographLight($request);
        $content = json_decode($data->getContent());
        $this->assertEquals(200, $data->getStatusCode());
        $this->assertEquals('тест значения', $content->text);

        //Обработка исключения
        $request = Request::create('/admin/ajax/TypographLight', 'POST', []);
        $this->controller->TypographLight($request);
    }
}
