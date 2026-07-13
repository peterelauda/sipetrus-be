<?php

namespace App\Repositories\Eloquents\Purchase;

use App\DTOs\Purchase\GetPurchasesDTO;
use App\Models\Purchase;
use App\Repositories\Contracts\Purchase\PurchaseRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;
use Carbon\Carbon;

class PurchaseRepository extends BaseRepository implements PurchaseRepositoryInterface
{
    public function __construct(
        Purchase $model
    ) {
        parent::__construct($model);
    }

    /**
     * Get purchases.
     */
    public function getPurchases(GetPurchasesDTO $dto)
    {
        return $this->query()
            ->with([
                'supplier'
            ])
            ->where(
                'store_id',
                auth()->user()->store_id
            )
            ->when(
                $dto->startDate && $dto->endDate,
                function ($query) use ($dto) {
                    $query->whereBetween(
                        'purchase_date',
                        [
                            $dto->startDate,
                            $dto->endDate
                        ]
                    );
                }
            )
            ->when(
                $dto->supplierId,
                function ($query) use ($dto) {
                    $query->where(
                        'supplier_id',
                        $dto->supplierId
                    );
                }
            )
            ->orderByDesc('purchase_date')
            ->paginate($dto->perPage);
    }

    /**
     * Latest invoice by date.
     */
    public function getLatestInvoiceByDate(
        Carbon $date
    ) {
        return $this->query()
            ->whereDate(
                'created_at',
                $date
            )
            ->latest()
            ->first();
    }
}
