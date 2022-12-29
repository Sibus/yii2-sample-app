<?php

declare(strict_types=1);

namespace app\forms;

use app\data\CustomDataProvider;
use app\entities\Book;
use Elastic\Elasticsearch\Client;
use OpenApi\Attributes as OA;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\data\Pagination;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

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

    public function __construct(private readonly Client $client, $config = [])
    {
        parent::__construct($config);
    }

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
        $dataProvider = new CustomDataProvider([
            'query' => Book::find(),
            'sort' => new Sort([
                'params' => ['sort' => $this->sort],
                'defaultOrder' => ['id' => SORT_ASC],
            ]),
            'pagination' => new Pagination([
                'params' => [
                    'page' => $this->page,
                    'pageSize' => $this->pageSize,
                ],
            ]),
        ]);
        $sort = $dataProvider->getSort();
        $pagination = $dataProvider->getPagination();

        $params = [
            'index' => 'book',
            'body' => [
                '_source' => ['id'],
                'from' => $pagination->getOffset(),
                'size' => $pagination->getLimit(),
                'sort' => array_map(
                    fn($attribute, $direction) => [$attribute => ($direction === SORT_ASC ? 'asc' : 'desc')],
                    array_keys($sort->getOrders()),
                    $sort->getOrders()
                ),
            ],
        ];

        if ($this->search) {
            $params['body']['query'] = [
                'bool' => [
                    'should' => [
                        ['query_string' => ['default_field' => 'name', 'query' => "*{$this->search}*"]],
                        ['query_string' => ['default_field' => 'author', 'query' => "*{$this->search}*"]],
                        ['query_string' => ['default_field' => 'genres', 'query' => "*{$this->search}*"]],
                    ],
                ],
            ];
        }

        $response = $this->client->search($params);
        $dataProvider->ids = ArrayHelper::getColumn($response['hits']['hits'], '_source.id');
        $dataProvider->totalCount = $response['hits']['total']['value'];

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
