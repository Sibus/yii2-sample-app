<?php

declare(strict_types=1);

namespace app\listeners;

use app\services\BookCreatedEvent;
use app\services\BookRatedEvent;
use app\services\search\BookIndexer;

class BookListener
{
    public function __construct(private readonly BookIndexer $indexer)
    {
    }

    public function __invoke(BookCreatedEvent|BookRatedEvent $event): void
    {
        $this->indexer->index($event->book);
    }
}
