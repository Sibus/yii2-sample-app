<?php

declare(strict_types=1);

namespace app\dispatchers;

use yii\di\Container;

class EventDispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    public function __construct(private readonly Container $container)
    {
    }

    public function addListener(string $eventName, string $listenerClass): void
    {
        $this->listeners[$eventName][] = function ($event) use ($listenerClass) {
            $listener = $this->container->get($listenerClass);
            $listener($event);
        };
    }

    public function dispatch(object $event): void
    {
        $eventName = get_class($event);
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                $listener($event);
            }
        }
    }
}
