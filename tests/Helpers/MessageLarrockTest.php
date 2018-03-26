<?php

namespace Larrock\Core\Exceptions;

use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\LarrockCoreServiceProvider;
use Orchestra\Testbench\TestCase;

class MessageLarrockTest extends TestCase
{
    protected $MessageLarrock;

    protected function setUp()
    {
        parent::setUp();

        $this->MessageLarrock = new MessageLarrock();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class
        ];
    }

    public function testSuccess()
    {
        $test = MessageLarrock::success('test', TRUE);
        $this->assertNull($test);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage test
     */
    public function testDanger()
    {
        $test = MessageLarrock::danger('test', TRUE, TRUE);
        $this->assertNull($test);
    }

    public function testWarning()
    {
        $test = MessageLarrock::warning('test', TRUE);
        $this->assertNull($test);
    }

    public function testNotice()
    {
        $test = MessageLarrock::notice('test', TRUE);
        $this->assertNull($test);
    }
}