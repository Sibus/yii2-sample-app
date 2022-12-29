<?php

declare(strict_types=1);

namespace app\data;

use yii\base\InvalidConfigException;
use yii\db\QueryInterface;

class CustomDataProvider extends \yii\data\ActiveDataProvider
{
    public $totalCount;
    public $ids;

    protected function prepareModels()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            if ($pagination->totalCount === 0) {
                return [];
            }
        }

        $models = (clone $this->query)
            ->andWhere(['id' => $this->ids])
            ->all($this->db);
        usort(
            $models,
            fn($a, $b) => array_search($a->id, $this->ids, true) - array_search($b->id, $this->ids, true)
        );
        return $models;
    }

    protected function prepareTotalCount()
    {
        if (!isset($this->totalCount)) {
            throw new InvalidConfigException('The "totalCount" property must be set.');
        }
        return $this->totalCount;
    }
}
