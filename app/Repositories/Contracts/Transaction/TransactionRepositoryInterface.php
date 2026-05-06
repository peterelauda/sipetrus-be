<?php

namespace App\Repositories\Contracts\Transaction;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Support\Carbon;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatestInvoiceByDate(Carbon $date);
}