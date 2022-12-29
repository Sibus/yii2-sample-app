<?php

declare(strict_types=1);

namespace app\services;

use app\entities\Book;
use app\entities\Estimate;
use app\forms\BookForm;
use app\forms\EstimateForm;
use app\services\search\BookIndexer;
use yii\db\ActiveRecordInterface;

class BookService
{
    public function __construct(private readonly BookIndexer $indexer)
    {
    }

    public function create(BookForm $form): Book
    {
        $book = Book::create($form->name, $form->author, $form->genres);
        $this->save($book);
        $this->indexer->index($book);
        return $book;
    }

    public function rate(int $id, EstimateForm $form): Book
    {
        $book = $this->find($id);
        $estimate = Estimate::create($book->id, $form->rating);
        \Yii::$app->db->transaction(function () use ($book, $estimate) {
            $this->save($estimate);
            $book->rating = $this->calculateRating($book->id);
            $this->save($book);
        });
        $this->indexer->index($book);

        return $book;
    }

    public function find($condition): Book
    {
        $book = Book::findOne($condition);
        if (!$book) {
            throw new EntityNotFoundException('Book is not found');
        }
        return $book;
    }

    private function calculateRating(int $id): ?float
    {
        $average = Estimate::find()->where(['book_id' => $id])->average('value');
        if (is_null($average)) {
            return null;
        }

        return round(floatval($average), 1);
    }

    private function save(ActiveRecordInterface $entity): void
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
}
