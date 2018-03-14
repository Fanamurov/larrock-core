<?php

namespace Larrock\Core\Tests;

use Larrock\Core\Component;
use Tests\TestCase;

class ComponentTest extends \PHPUnit\Framework\TestCase
{
    protected $component;

    protected function setUp()
    {
        parent::setUp();

        $this->createApplication();

        $this->component = new Component();

    }

    public function tearDown() {
        //unset($this->controller, $this->test_table);
    }

    public function testGetConfig()
    {
        $attributes = ['name', 'title', 'description', 'table', 'rows', 'customMediaConversions', 'model', 'active',
            'plugins_backend', 'plugins_front', 'settings', 'searchable', 'tabs', 'tabs_data', 'valid'];
        foreach ($attributes as $attribute){
            $this->assertClassHasAttribute($attribute, \get_class($this->component));
        }
    }
}
