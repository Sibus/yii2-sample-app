<?php

declare(strict_types=1);

namespace app\controllers;

use OpenApi\Attributes as OA;
use yii\web\Controller;

#[OA\Schema(schema: "Links", properties: [
    new OA\Property("self", example: "http://localhost/books?page=1&pageSize=5"),
    new OA\Property("first", example: "http://localhost/books?page=1&pageSize=5"),
    new OA\Property("last", example: "http://localhost/books?page=18&pageSize=5"),
    new OA\Property("next", example: "http://localhost/books?page=2&pageSize=5"),
])]
#[OA\Schema(schema: "Meta", properties: [
    new OA\Property("totalCount", type: "integer", example: 88),
    new OA\Property("pageCount", type: "integer", example: 18),
    new OA\Property("currentPage", type: "integer", example: 1),
    new OA\Property("perPage", type: "integer", example: 5),
])]
#[OA\Schema(schema: "Error", properties: [
    new OA\Property(property: "field", type: "string", example: "name"),
    new OA\Property(property: "message", type: "string", example: "Name is required"),
])]
#[OA\Schema(schema: "ErrorList", type: "array", items: new OA\Items(ref: "#/components/schemas/Error"))]
#[OA\Schema(schema: "Exception", properties: [
    new OA\Property(property: "name", type: "string", example: "Unprocessable entity"),
    new OA\Property(property: "message", type: "string", example: "Entity is not found."),
    new OA\Property(property: "code", type: "integer", example: 0),
    new OA\Property(property: "status", type: "integer", example: 422),
])]
class OpenApiController extends Controller
{
    public function actionDocument()
    {
        $openapi = \OpenApi\Generator::scan([
            \Yii::getAlias('@app/controllers'),
            \Yii::getAlias('@app/entities'),
            \Yii::getAlias('@app/forms'),
        ]);

        return $this->response->sendContentAsFile($openapi->toYaml(), 'openapi.yaml', [
            'Content-Type' => 'application/x-yaml',
            'inline' => true,
        ]);
    }
}
