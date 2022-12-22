<?php

namespace app\controllers;

use OpenApi\Attributes as OA;
use yii\web\Controller;

/**
 * @OA\OpenApi(
 *     security={{"token_scheme": {}}},
 * )
 */
#[OA\Info(version: "0.1", title: "Book API")]
#[OA\SecurityScheme(
    securityScheme: "token_scheme",
    type: "apiKey",
    name: "token",
    in: "header",
)]
class SiteController extends Controller
{
    public function actionIndex()
    {
        return [
            'name' => 'JSON API',
        ];
    }
}
