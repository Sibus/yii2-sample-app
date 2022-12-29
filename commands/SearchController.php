<?php

namespace app\commands;

use app\entities\Book;
use app\services\search\BookIndexer;
use yii\console\Controller;
use yii\console\ExitCode;

class SearchController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly BookIndexer $indexer,
        $config = [],
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function actionReindex(): int
    {
        $this->indexer->clear();

        $query = Book::find();
        foreach ($query->batch() as $books) {
            $this->indexer->bulkIndex($books);
        }

        return ExitCode::OK;
    }
}
