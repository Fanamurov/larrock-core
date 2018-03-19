<?php

namespace Larrock\Core\Tests\Helpers;

use Larrock\Core\Helpers\CustomPathGenerator;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
use Orchestra\Testbench\TestCase;
use DB;
use Spatie\MediaLibrary\Media;

class CustomPathGeneratorTest extends TestCase
{
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

        $seed = new CreateMediaDatabase();
        $seed->setUpMediaDatabase();
    }

    public function testGetPath()
    {
        $CustomPathGenerator = new CustomPathGenerator();
        $test = $CustomPathGenerator->getPath(Media::whereId(1)->first());
        $this->assertEquals('config/', $test);
    }

    public function testGetPathForConversions()
    {
        $CustomPathGenerator = new CustomPathGenerator();
        $test = $CustomPathGenerator->getPathForConversions(Media::whereId(1)->first());
        $this->assertEquals('config/test/', $test);
    }
}