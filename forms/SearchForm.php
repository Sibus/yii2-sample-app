<?php

declare(strict_types=1);

namespace app\forms;

use app\entities\Book;
use OpenApi\Attributes as OA;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

#[OA\Schema()]
class SearchForm extends Model
{
    #[OA\Property(type: "string", example: "name")]
    public $sort;

    #[OA\Property(type: "string", example: "Улитка на склоне")]
    public $search;

    #[OA\Property(type: "integer", example: 2)]
    public $page;

    #[OA\Property(type: "integer", example: 50)]
    public $pageSize;

    public function rules(): array
    {
        return [
            ['search', 'string'],
            ['sort', 'string'],
            [
                'sort',
                'in',
                'range' => $this->sortable(),
                'message' => 'Значение должно быть одно из списка: ' . implode(', ', $this->sortable()),
            ],
            ['page', 'integer'],
            ['pageSize', 'integer'],
        ];
    }

    public function search(): DataProviderInterface
    {
        $q = Book::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $q,
            'sort' => [
                'sortParam' => 'sort',
                'params' => ['sort' => $this->sort],
                'defaultOrder' => ['id' => SORT_ASC],
            ],
            'pagination' => [
                'pageParam' => 'page',
                'pageSizeParam' => 'pageSize',
                'params' => [
                    'page' => $this->page,
                    'pageSize' => $this->pageSize,
                ],
            ],
        ]);

        if ($this->search) {
            $q->from([Book::tableName(), 'jsonb_array_elements(genres)']);
            $q->select(Book::tableName() . '.*');
            $q->distinct();
            $q->andWhere(
                'name ILIKE :search OR author ILIKE :search OR value::text ILIKE :search',
                ['search' => "%$this->search%"],
            );
        }

        return $dataProvider;
    }

    private function sortable(): array
    {
        $items = [
            'name',
            'author',
            'rating',
            'created_at',
        ];
        $result = [];
        foreach ($items as $item) {
            $result[] = $item;
            $result[] = '-' . $item;
        }
        return $result;
    }
}
