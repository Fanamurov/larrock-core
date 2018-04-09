<?php

namespace Larrock\Core\Tests\Helpers\FormBuilder;

use Larrock\ComponentFeed\Facades\LarrockFeed;
use Larrock\ComponentFeed\LarrockComponentFeedServiceProvider;
use Larrock\ComponentFeed\Models\Feed;
use Larrock\Core\Helpers\FormBuilder\FormTags;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Models\Config;
use Larrock\Core\Models\Seo;
use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Tests\DatabaseTest\CreateConfigDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateFeedDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateLinkDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateSeoDatabase;

class FormTagsTest extends \Orchestra\Testbench\TestCase
{
    /** @var FormTags */
    protected $FormTags;

    protected function setUp()
    {
        parent::setUp();

        $this->FormTags = new FormTags('test_name', 'test_title');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->FormTags);
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
            LarrockCoreServiceProvider::class,
            LarrockComponentFeedServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockFeed' => LarrockFeed::class,
        ];
    }

    public function testSetModels()
    {
        $this->FormTags->setModels(Config::class, Seo::class);
        $this->assertEquals(Config::class, $this->FormTags->modelParent);
        $this->assertEquals(Seo::class, $this->FormTags->modelChild);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function testSetModelChildWhere()
    {
        $this->FormTags->setModelChildWhere('test_key', 'test_value');
        $this->assertEquals('test_key', $this->FormTags->modelChildWhereKey);
        $this->assertEquals('test_value', $this->FormTags->modelChildWhereValue);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function testSetMaxItems()
    {
        $this->FormTags->setMaxItems(1);
        $this->assertEquals(1, $this->FormTags->maxItems);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function testSetAllowCreate()
    {
        $this->FormTags->setAllowCreate();
        $this->assertTrue($this->FormTags->allowCreate);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function testSetDeleteIfNoLink()
    {
        $this->FormTags->setDeleteIfNoLink();
        $this->assertTrue($this->FormTags->deleteIfNoLink);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function testSetCostValue()
    {
        $this->FormTags->setCostValue();
        $this->assertTrue($this->FormTags->costValue);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function testSetTitleRow()
    {
        $this->FormTags->setTitleRow('test');
        $this->assertEquals('test', $this->FormTags->titleRow);
        $this->assertInstanceOf(FBElement::class, $this->FormTags);
        $this->assertInstanceOf(FormTags::class, $this->FormTags);
    }

    public function test__toString()
    {
        $seed = new CreateSeoDatabase();
        $seed->setUpSeoDatabase();

        $seed = new CreateFeedDatabase();
        $seed->setUpFeedDatabase();

        $seed = new CreateLinkDatabase();
        $seed->setUpLinkDatabase();

        $this->FormTags->setModels(Feed::class, Seo::class);
        $this->FormTags->setModelChildWhere('id', 1);
        $this->assertNotEmpty($this->FormTags->__toString());
    }
}