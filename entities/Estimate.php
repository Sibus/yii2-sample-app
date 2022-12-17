<?php

declare(strict_types=1);

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $book_id
 * @property int $value
 * @property int $created_at
 */
class Estimate extends ActiveRecord
{
    public static function create(int $bookId, int $value): self
    {
        $estimate = new static();
        $estimate->book_id = $bookId;
        $estimate->ensureValueIsValid($value);
        $estimate->value = $value;
        $estimate->created_at = time();
        return $estimate;
    }

    public static function tableName(): string
    {
        return '{{%estimates}}';
    }

    private function ensureValueIsValid(int $value): void
    {
        if ($value < 1 || 5 < $value) {
            throw new \UnexpectedValueException("Estimate must be between 1 and 5. {$value} given");
        }
    }
}
