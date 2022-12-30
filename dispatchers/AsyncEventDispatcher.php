<?php

declare(strict_types=1);

namespace app\dispatchers;

use app\jobs\AsyncEventJob;
use yii\queue\Queue;

class AsyncEventDispatcher implements EventDispatcherInterface
{
    public function __construct(private readonly Queue $queue)
    {
    }

    public function dispatch(object $event)
    {
        $this->queue->push(new AsyncEventJob($event));
    }
}
