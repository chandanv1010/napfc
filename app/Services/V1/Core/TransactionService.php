<?php

namespace App\Services\V1\Core;

use App\Services\V1\BaseService;
use App\Repositories\Core\TransactionRepository;

class TransactionService extends BaseService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['created_at'] = $request->input('created_at');
        $condition['language_id'] = $this->currentLanguage();
        $condition['filters'] = [
            'status' => $request->input('status'),
            'type' => $request->input('type'),
        ];

        $perPage = $request->integer('perpage') ?: 20;

        return $this->transactionRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perPage,
            ['path' => 'transaction/index'],
            ['transactions.id', 'desc'],
        );
    }

    private function paginateSelect()
    {
        return [
            'transactions.id',
            'transactions.transaction_code',
            'transactions.product_id',
            'transactions.customer_id',
            'transactions.account',
            'transactions.amount',
            'transactions.status',
            'transactions.type',
            'transactions.description',
            'transactions.paid_at',
            'transactions.quantity',
            'transactions.created_at',
        ];
    }
}
