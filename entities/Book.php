<?php

declare(strict_types=1);

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $author
 * @property float|null $rating
 * @property string $genres
 * @property int $created_at
 * @property int|null $updated_at
 *
 * @property Estimate[] $estimates
 */
class Book extends ActiveRecord
{
    /**
     * @param string $name
     * @param string $author
     * @param string[] $genres
     * @return static
     */
    public static function create(string $name, string $author, array $genres): self
    {
        $book = new static();
        $book->name = $name;
        $book->author = $author;
        $book->ensureGenresAreValid($genres);
        $book->genres = $genres;
        $book->created_at = time();
        return $book;
    }

    public function getEstimates(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Estimate::class, ['book_id' => 'id']);
    }

    public static function tableName(): string
    {
        return '{{%books}}';
    }

    private function ensureGenresAreValid(array $array): void
    {
        foreach ($array as $item) {
            if (!is_string($item)) {
                throw new \InvalidArgumentException("{$item} is not valid genre.");
            }
        }
    }
}
