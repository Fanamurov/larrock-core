<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Helpers\FormBuilder\FormCategory;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Models\Config;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;

class FormCategoryTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormCategory */
    protected $FormCategory;

    protected function setUp()
    {
        parent::setUp();

        $this->FormCategory = new FormCategory('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormCategory);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    public function testSetMaxItems()
    {
        $this->FormCategory->setMaxItems(1);
        $this->assertEquals(1, $this->FormCategory->max_items);
        $this->assertInstanceOf(FBElement::class, $this->FormCategory);
        $this->assertInstanceOf(FormCategory::class, $this->FormCategory);
    }

    public function setAllowEmpty()
    {
        $this->FormCategory->setAllowEmpty();
        $this->assertTrue($this->FormCategory->setAllowEmpty()->allow_empty);
        $this->assertInstanceOf(FBElement::class, $this->FormCategory->setAllowEmpty());
        $this->assertInstanceOf(FormCategory::class, $this->FormCategory->setAllowEmpty());
    }

    /**
     * @expectedException \Larrock\Core\Exceptions\LarrockFormBuilderRowException
     * @expectedExceptionMessage Поля model, relation_name не установлены через setConnect()
     */
    public function testRender()
    {
        $seed = new CreateConfigDatabase();
        $seed->setUpTestDatabase();

        $this->FormCategory->render($this->FormCategory, collect([]));

        $this->FormCategory->setConnect(Config::class, 'test_relation');
        $data = new Config();
        $data->test_relation = collect([]);

        $this->assertNotEmpty($this->FormCategory->render($this->FormCategory, $data));
    }
}