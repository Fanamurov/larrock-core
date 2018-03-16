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

    public function testRender()
    {
        $data = new Config();
        $data->test_name = collect([]);
        $this->assertNotEmpty($this->FormSelectKey->render($this->FormSelectKey, $data));
    }
}