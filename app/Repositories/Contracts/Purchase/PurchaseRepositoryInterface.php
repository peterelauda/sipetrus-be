<?php

namespace App\Repositories\Contracts\Purchase;

use App\DTOs\Purchase\GetPurchasesDTO;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Carbon\Carbon;

interface PurchaseRepositoryInterface extends BaseRepositoryInterface
{
    public function getPurchases(GetPurchasesDTO $dto);
    public function getLatestInvoiceByDate(Carbon $date);
}