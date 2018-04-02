<?php

namespace Larrock\Core\Listeners;

use Larrock\Core\Events\ComponentItemStored;
use Larrock\Core\Events\ComponentItemUpdated;
use Larrock\Core\Events\ComponentItemDestroyed;

class CoreEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ComponentItemDestroyed::class,
            'Larrock\Core\Plugins\ComponentPlugin@detach'
        );

        $events->listen(
            ComponentItemUpdated::class,
            'Larrock\Core\Plugins\ComponentPlugin@attach'
        );

        $events->listen(
            ComponentItemStored::class,
            'Larrock\Core\Plugins\ComponentPlugin@attach'
        );
    }
}
