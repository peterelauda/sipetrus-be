<?php

namespace App\DTOs\Purchase;

class PurchaseItemDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $productId,
        public readonly int $qty,
        public readonly float $costPrice,
        public readonly string $expiredDate,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int) $data['product_id'],
            qty: (int) $data['qty'],
            costPrice: (float) $data['cost_price'],
            expiredDate: (string) $data['expired_date'],
        );
    }
}
