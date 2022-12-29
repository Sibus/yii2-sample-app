<?php

declare(strict_types=1);

namespace app\services;

use app\entities\Book;

class BookCreatedEvent
{
    public function __construct(public readonly Book $book)
    {
    }
}
