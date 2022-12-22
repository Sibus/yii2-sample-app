<?php

declare(strict_types=1);

namespace app\controllers;

use app\entities\Book;
use app\forms\BookForm;
use app\forms\EstimateForm;
use app\forms\SearchForm;
use app\services\BookService;
use OpenApi\Attributes as OA;
use Yii;

class BookController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly BookService $service,
        $config = [],
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [];
    }

    #[OA\Get(path: "/books")]
    #[OA\Parameter(name: "sort", in: "query", schema: new OA\Schema(type: "string", example: "rating"))]
    #[OA\Parameter(name: "search", in: "query", schema: new OA\Schema(type: "string", example: "Улитка"))]
    #[OA\Parameter(name: "page", in: "query", schema: new OA\Schema(type: "integer", example: 1))]
    #[OA\Parameter(name: "pageSize", in: "query", schema: new OA\Schema(type: "integer", example: 5))]
    #[OA\Response(response: 200, description: "Success", content: new OA\JsonContent(properties: [
        new OA\Property("items", ref: "#/components/schemas/BookList"),
        new OA\Property("_links", ref: "#/components/schemas/Links"),
        new OA\Property("_meta", ref: "#/components/schemas/Meta")
    ]))]
    #[OA\Response(response: 422, description: "Data Validation Failed", content: new OA\JsonContent(ref: "#/components/schemas/ErrorList"))]
    public function actionIndex()
    {
        $form = new SearchForm();
        $form->load(Yii::$app->request->get(), '');
        if ($form->validate()) {
            return $form->search();
        }
        return $form;
    }

    #[OA\Get(path: "/books/{id}")]
    #[OA\PathParameter(name: "id", required: true, schema: new OA\Schema(ref: "#/components/schemas/Book/properties/id"))]
    #[OA\Response(response: 200, description: "Success", content: new OA\JsonContent(ref: "#/components/schemas/Book"))]
    #[OA\Response(response: 404, description: "Book is not found")]
    public function actionView($id): Book
    {
        return $this->service->find($id);
    }

    #[OA\Post(path: "/books")]
    #[OA\RequestBody(content: new OA\JsonContent(ref: "#/components/schemas/BookForm"))]
    #[OA\Response(response: 200, description: "Success")]
    #[OA\Response(response: 422, description: "Data Validation Failed", content: new OA\JsonContent(oneOf: [
        new OA\Schema(ref: "#/components/schemas/ErrorList"),
        new OA\Schema(ref: "#/components/schemas/Exception"),
    ]))]
    public function actionCreate()
    {
        $form = new BookForm();
        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            return $this->service->create($form);
        }
        return $form;
    }

    #[OA\Post(path: "/books/{id}/rating")]
    #[OA\PathParameter(name: "id", required: true, schema: new OA\Schema(ref: "#/components/schemas/Book/properties/id"))]
    #[OA\RequestBody(content: new OA\JsonContent(ref: "#/components/schemas/EstimateForm"))]
    #[OA\Response(response: 200, description: "Success", content: new OA\JsonContent(ref: "#/components/schemas/Book"))]
    #[OA\Response(response: 404, description: "Book is not found")]
    #[OA\Response(response: 422, description: "Data Validation Failed", content: new OA\JsonContent(oneOf: [
        new OA\Schema(ref: "#/components/schemas/ErrorList"),
        new OA\Schema(ref: "#/components/schemas/Exception"),
    ]))]
    public function actionRate($id)
    {
        $form = new EstimateForm();
        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            return $this->service->rate((int) $id, $form);
        }
        return $form;
    }
}
