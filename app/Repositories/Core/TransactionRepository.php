<?php

namespace App\Repositories\Core;

use App\Models\Transaction;
use App\Repositories\BaseRepository;

class TransactionRepository extends BaseRepository
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'],
        array $condition = [],
        int $perPage = 20,
        array $extend = [],
        array $orderBy = ['transactions.id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = []
    ) {
        $query = $this->model->select($column);

        $query->with([
            'products' => function ($productQuery) use ($condition) {
                $productQuery->with(['languages' => function ($languageQuery) use ($condition) {
                    if (isset($condition['language_id'])) {
                        $languageQuery->wherePivot('language_id', $condition['language_id']);
                    }
                }]);
            },
            'customers',
        ]);

        if (!empty($condition['keyword'])) {
            $keyword = $condition['keyword'];
            $query->where(function ($subQuery) use ($keyword, $condition) {
                $subQuery->where('transactions.transaction_code', 'LIKE', "%{$keyword}%")
                    ->orWhere('transactions.account', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('products', function ($productQuery) use ($keyword) {
                        $productQuery->where('products.code', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('products.languages', function ($languageQuery) use ($keyword, $condition) {
                        if (isset($condition['language_id'])) {
                            $languageQuery->wherePivot('language_id', $condition['language_id']);
                        }
                        $languageQuery->wherePivot('name', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('customers', function ($customerQuery) use ($keyword) {
                        $customerQuery->where(function ($customerSubQuery) use ($keyword) {
                            $customerSubQuery->where('name', 'LIKE', "%{$keyword}%")
                                ->orWhere('email', 'LIKE', "%{$keyword}%")
                                ->orWhere('phone', 'LIKE', "%{$keyword}%");
                        });
                    });
            });
        }

        $allowedFilters = ['status', 'type'];
        if (!empty($condition['filters'])) {
            foreach ($condition['filters'] as $field => $value) {
                if (!empty($value) && $value !== 'none' && in_array($field, $allowedFilters, true)) {
                    $query->where("transactions.$field", $value);
                }
            }
        }

        $query->customerCreatedAt($condition['created_at'] ?? null);

        $orderByField = $orderBy[0] ?? 'transactions.id';
        $orderByDirection = $orderBy[1] ?? 'DESC';
        $perPage = $perPage > 0 ? $perPage : 20;
        $path = $extend['path'] ?? 'transaction/index';

        return $query
            ->orderBy($orderByField, $orderByDirection)
            ->paginate($perPage)
            ->withQueryString()
            ->withPath(env('APP_URL') . $path);
    }
}
