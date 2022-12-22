<?php

declare(strict_types=1);

namespace app\forms;

use OpenApi\Attributes as OA;
use yii\base\Model;

#[OA\Schema()]
class BookForm extends Model
{
    #[OA\Property(ref: "#/components/schemas/Book/properties/name")]
    public $name;

    #[OA\Property(ref: "#/components/schemas/Book/properties/author")]
    public $author;

    #[OA\Property(ref: "#/components/schemas/Book/properties/genres")]
    public $genres = [];

    public function rules(): array
    {
        return [
            [['name', 'author'], 'required'],
            [['name', 'author'], 'string', 'max' => 255],
            [['genres'], 'each', 'rule' => ['string']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Title',
            'author' => 'Author',
            'genres' => 'Genres',
        ];
    }
}
