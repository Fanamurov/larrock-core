<?php

namespace Larrock\Core\Tests\Listeners;

use Larrock\Core\Listeners\CoreEventSubscriber;
use Orchestra\Testbench\TestCase;
use Illuminate\Events\Dispatcher;

class CoreEventSubscriberTest extends TestCase
{
    public function testSubscribe()
    {
        $CoreEventSubscriber = new CoreEventSubscriber();
        $event = new Dispatcher();
        $CoreEventSubscriber->subscribe($event);
        $test = $event->getListeners('Larrock\Core\Events\ComponentItemDestroyed');
        $this->assertInstanceOf(\Closure::class, $test[0]);

        $test = $event->getListeners('Larrock\Core\Events\ComponentItemUpdated');
        $this->assertInstanceOf(\Closure::class, $test[0]);
    }
}