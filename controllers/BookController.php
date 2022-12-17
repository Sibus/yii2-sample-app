<?php

declare(strict_types=1);

namespace app\controllers;

use app\entities\Book;
use app\forms\BookForm;
use app\forms\EstimateForm;
use app\forms\SearchForm;
use app\services\BookService;
use app\services\EntityNotFoundException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

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

    public function actionIndex()
    {
        $form = new SearchForm();
        $form->load(Yii::$app->request->get(), '');
        if ($form->validate()) {
            return $form->search();
        }
        return $form;
    }

    public function actionView($id): Book
    {
        try {
            return $this->service->find($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage(), previous: $e);
        }
    }

    public function actionCreate()
    {
        $form = new BookForm();
        try {
            if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
                return $this->service->create($form);
            }
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new \yii\web\UnprocessableEntityHttpException($e->getMessage(), previous: $e);
        }
        return $form;
    }

    public function actionRate($id)
    {
        $form = new EstimateForm();
        try {
            if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
                return $this->service->rate((int) $id, $form);
            }
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new \yii\web\UnprocessableEntityHttpException($e->getMessage(), previous: $e);
        }

        return $form;
    }
}
