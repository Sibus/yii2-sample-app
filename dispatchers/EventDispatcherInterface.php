<?php

declare(strict_types=1);

namespace app\dispatchers;

interface EventDispatcherInterface
{
    public function dispatch(object $event);
}
