<?php

declare(strict_types=1);

namespace app\entities;

use OpenApi\Attributes as OA;
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
#[OA\Schema(schema: "BookList", type: "array", items: new OA\Items(ref: "#/components/schemas/Book"))]
#[OA\Schema(
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", maxLength: 255, example: "Улитка на склоне"),
        new OA\Property(property: "author", type: "string", maxLength: 255, example: "Аркадий и Борис Стругацкие"),
        new OA\Property(property: "rating", type: "float", maximum: 5, minimum: 1, example: 4.5, nullable: true),
        new OA\Property(property: "genres", type: "array", items: new OA\Items(type: "string"), example: ["Фантастика", "Приключение"]),
        new OA\Property(property: "created_at", type: "integer", example: 1671442737),
        new OA\Property(property: "updated_at", type: "integer", example: null, nullable: true),
    ],
)]
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
