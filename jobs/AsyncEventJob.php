<?php

declare(strict_types=1);

namespace app\jobs;

use yii\queue\JobInterface;

class AsyncEventJob implements JobInterface
{
    public function __construct(public readonly object $event)
    {
    }

    public function execute($queue)
    {
        $handler = \Yii::createObject(AsyncEventJobHandler::class);
        $handler->handle($this);
    }
}
