<?php

declare(strict_types=1);

namespace app\forms;

use yii\base\Model;

class EstimateForm extends Model
{
    public $rating;

    public function rules(): array
    {
        return [
            ['rating', 'required'],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
        ];
    }
}
