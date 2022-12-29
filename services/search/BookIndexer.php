<?php

declare(strict_types=1);

namespace app\services\search;

use app\entities\Book;
use Elastic\Elasticsearch\Client;

class BookIndexer
{
    const BOOK_INDEX = 'book';

    public function __construct(private readonly Client $client)
    {
    }

    public function index(Book $book): void
    {
        $this->client->index([
            'index' => self::BOOK_INDEX,
            'id' => $book->id,
            'body' => $book->toArray(),
        ]);
    }

    /**
     * @param Book[] $books
     */
    public function bulkIndex(array $books): void
    {
        $body = [];
        foreach ($books as $book) {
            $body[] = ['index' => ['_index' => self::BOOK_INDEX]];
            $body[] = $book->toArray();
        }
        $this->client->bulk(['body' => $body]);
    }

    public function clear(): void
    {
        $response = $this->client->indices()->exists(['index' => self::BOOK_INDEX]);
        if ($response->asBool()) {
            $this->client->deleteByQuery([
                'index' => self::BOOK_INDEX,
                'body' => [
                    'query' => [
                        'match_all' => new \stdClass(),
                    ],
                ],
            ]);
        }
    }
}
