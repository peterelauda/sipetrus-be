<?php

namespace App\Repositories\Eloquents\Transaction;

use App\Models\TransactionItem;
use App\Repositories\Contracts\Transaction\TransactionItemRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class TransactionItemRepository extends BaseRepository implements TransactionItemRepositoryInterface
{
    public function __construct(TransactionItem $model)
    {
        $this->model = $model;
    }
}