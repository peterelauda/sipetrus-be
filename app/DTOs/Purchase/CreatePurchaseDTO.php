<?php

namespace App\DTOs\Purchase;

class CreatePurchaseDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $supplierId,
        public readonly string $purchaseDate,
        public readonly array $items,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            supplierId: (int) $request->validated('supplier_id'),
            purchaseDate: (string) $request->validated('purchase_date'),
            items: array_map(
                fn($item) => PurchaseItemDTO::fromArray($item),
                $request->validated('items')
            ),
        );
    }
}
