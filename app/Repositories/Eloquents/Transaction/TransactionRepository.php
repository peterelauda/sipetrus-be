<?php

namespace App\Repositories\Eloquents\Transaction;

use App\Models\Transaction;
use App\Repositories\Contracts\Transaction\TransactionRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;
use Illuminate\Support\Carbon;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function getLatestInvoiceByDate(Carbon $date)
    {
        return $this->model
            ->whereDate('created_at', $date->toDateString())
            ->orderBy('id', 'desc')
            ->first();
    }
}