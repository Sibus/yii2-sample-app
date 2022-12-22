<?php

declare(strict_types=1);

namespace app\forms;

use OpenApi\Attributes as OA;
use yii\base\Model;

#[OA\Schema()]
class EstimateForm extends Model
{
    #[OA\Property(type: "integer", maximum: 5, minimum: 1, example: 4.5)]
    public $rating;

    public function rules(): array
    {
        return [
            ['rating', 'required'],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
        ];
    }
}
