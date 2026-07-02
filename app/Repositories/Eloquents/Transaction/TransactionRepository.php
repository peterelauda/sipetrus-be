<?php

namespace App\Repositories\Eloquents\Transaction;

use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
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

    public function getTransactions(
        int $page,
        int $limit,
        Carbon $startDate,
        Carbon $endDate,
        PaymentMethodEnum $paymentMethod
    ) {
        return $this->model
            ->where('store_id', auth()->user()->store_id)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($paymentMethod, function ($query) use ($paymentMethod) {
                $query->where('payment_method', $paymentMethod->value);
            })
            ->orderBy('id', 'desc')
            ->paginate(
                perPage: $limit,
                page: $page
            );
    }

    public function getTransactionDetail(string $id)
    {
        return $this->model
            ->with([
                'items.product'
            ])
            ->find($id);
    }

    public function getTransactionById(string $id, int $storeId)
    {
        return $this->model
            ->with([
                'items.batchAllocations'
            ])
            ->where('id', $id)
            ->where('store_id', $storeId)
            ->first();
    }

    public function cancelTransactionById(string $id)
    {
        $this->model
            ->where('id', $id)
            ->where('store_id', auth()->user()->store_id)
            ->update(['status' => PaymentStatusEnum::CANCELLED]);
    }
}