<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormSelectKey;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Models\Config;

class FormSelectKeyTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormSelectKey */
    protected $FormSelectKey;

    protected function setUp()
    {
        parent::setUp();

        $this->FormSelectKey = new FormSelectKey('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormSelectKey);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSetOptions()
    {
        $this->FormSelectKey->setOptions(['test']);
        $this->assertEquals(['test'], $this->FormSelectKey->options);
        $this->assertInstanceOf(FBElement::class, $this->FormSelectKey);
        $this->assertInstanceOf(FormSelectKey::class, $this->FormSelectKey);
    }

    public function testSetOptionsTitle()
    {
        $this->FormSelectKey->setOptionsTitle('test');
        $this->assertEquals('test', $this->FormSelectKey->option_title);
        $this->assertInstanceOf(FBElement::class, $this->FormSelectKey);
        $this->assertInstanceOf(FormSelectKey::class, $this->FormSelectKey);
    }

    public function testSetOptionsKey()
    {
        $this->FormSelectKey->setOptionsKey('test');
        $this->assertEquals('test', $this->FormSelectKey->option_key);
        $this->assertInstanceOf(FBElement::class, $this->FormSelectKey);
        $this->assertInstanceOf(FormSelectKey::class, $this->FormSelectKey);
    }

    public function testSetConnect()
    {
        $this->FormSelectKey->setConnect(Config::class, 'test_relation', 'test_group');
        $this->assertEquals(Config::class, $this->FormSelectKey->connect->model);
        $this->assertEquals('test_relation', $this->FormSelectKey->connect->relation_name);
        $this->assertEquals('test_group', $this->FormSelectKey->connect->group_by);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FormSelectKey);
    }

    /**
     * @expectedException \Larrock\Core\Exceptions\LarrockFormBuilderRowException
     * @expectedExceptionMessage У поля test_name сначала нужно определить setConnect
     */
    public function testSetWhereConnect()
    {
        $this->FormSelectKey->setConnect(Config::class, 'test_relation', 'test_group');
        $this->FormSelectKey->setWhereConnect('test_key', 'test_value');
        $this->assertEquals('test_key', $this->FormSelectKey->connect->where_key);
        $this->assertEquals('test_value', $this->FormSelectKey->connect->where_value);
        $this->assertInstanceOf('Larrock\Core\Helpers\FormBuilder\FBElement', $this->FormSelectKey);

        $this->FormSelectKey = new FormSelectKey('test_name', 'test_title');
        $this->FormSelectKey->setWhereConnect('test_key', 'test_value');
    }

    public function testRender()
    {
        $this->FormSelectKey->setDefaultValue('test');
        $data = new Config();
        $data->test_name = collect([]);
        $this->assertNotEmpty($this->FormSelectKey->render($this->FormSelectKey, $data));
    }
}