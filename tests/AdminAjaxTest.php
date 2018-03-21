<?php

namespace Larrock\Core\Tests;

use \Larrock\Core\AdminAjax;
use Illuminate\Http\Request;
use DB;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateUserDatabase;

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
