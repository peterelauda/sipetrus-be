<?php

namespace App\Repositories\Eloquents\Transaction;

use App\Models\TransactionItemBatch;
use App\Repositories\Contracts\Transaction\TxItemBatchRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class TxItemBatchRepository extends BaseRepository implements TxItemBatchRepositoryInterface
{
    public function __construct(TransactionItemBatch $model)
    {
        $this->model = $model;
    }
}