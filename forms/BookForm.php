<?php

declare(strict_types=1);

namespace app\forms;

use yii\base\Model;

class BookForm extends Model
{
    public $name;
    public $author;
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
