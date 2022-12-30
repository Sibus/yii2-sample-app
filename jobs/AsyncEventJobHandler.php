<?php

declare(strict_types=1);

namespace app\jobs;

use app\dispatchers\EventDispatcherInterface;

class AsyncEventJobHandler
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function handle(AsyncEventJob $job): void
    {
        $this->dispatcher->dispatch($job->event);
    }
}
