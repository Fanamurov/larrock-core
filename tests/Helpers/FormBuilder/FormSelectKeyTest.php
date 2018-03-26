<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\Core\Helpers\FormBuilder\FormSelectKey;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Models\Config;
use Larrock\Core\Models\Seo;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;

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

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
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

    public function test__toString()
    {
        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();

        \DB::connection()->table('seo')->insert([
            'seo_title' => 'test2_t',
            'seo_description' => 'test2_d',
            'seo_keywords' => 'test2_k',
            'seo_id_connect' => 2,
            'seo_url_connect' => 'test2_uc',
            'seo_type_connect' => 'test2_st',
        ]);

        $row = new FormSelectKey('type', 'Тип меню');
        $test = $row->setValid('required')
            ->setConnect(Seo::class, NULL, 'seo_type_connect')->setDefaultValue('default')
            ->setOptionsTitle('seo_type_connect')->setOptionsKey('seo_id_connect')
            ->setCssClassGroup('uk-width-1-1 uk-width-1-3@m')->setFillable();

        $this->assertNotNull((string)$test);
    }
}