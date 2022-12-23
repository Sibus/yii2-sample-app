<?php

namespace app\commands;

use app\forms\BookForm;
use app\forms\EstimateForm;
use app\services\BookService;
use Faker\Factory;
use yii\console\Controller;
use yii\console\ExitCode;

class SeederController extends Controller
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

    public function actionSeed(): int
    {
        $faker = Factory::create();
        $bookForm = new BookForm();
        $bookForm->name = "Улитка на склоне";
        $bookForm->author = "Аркадий и Борис Стругацкие";
        $bookForm->genres = ["Фантастика", "Приключение"];
        $book = $this->service->create($bookForm);
        for ($j = 0; $j < 10; $j++) {
            $rateForm = new EstimateForm();
            $rateForm->rating = rand(1, 5);
            $this->service->rate($book->id, $rateForm);
        }
        for ($i = 0; $i < 100; $i++) {
            $bookForm = new BookForm();
            $bookForm->name = $faker->sentence(3);
            $bookForm->author = $faker->name();
            $bookForm->genres = $faker->words();
            $book = $this->service->create($bookForm);
            for ($j = 0; $j < 10; $j++) {
                $rateForm = new EstimateForm();
                $rateForm->rating = rand(1, 5);
                $this->service->rate($book->id, $rateForm);
            }
        }

        return ExitCode::OK;
    }
}
