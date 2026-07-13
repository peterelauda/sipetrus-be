<?php

namespace App\Repositories\Eloquents\Purchase;

use App\Models\PurchaseItem;
use App\Repositories\Contracts\Purchase\PurchaseItemRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class PurchaseItemRepository extends BaseRepository implements PurchaseItemRepositoryInterface
{
    public function __construct(
        PurchaseItem $model
    ) {
        parent::__construct($model);
    }
}
