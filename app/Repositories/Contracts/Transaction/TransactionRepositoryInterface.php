<?php

namespace App\Repositories\Contracts\Transaction;

use App\Enums\PaymentMethodEnum;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Support\Carbon;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatestInvoiceByDate(Carbon $date);
    public function getTransactions(int $page, int $limit, Carbon $startDate, Carbon $endDate, PaymentMethodEnum $paymentMethod);
}