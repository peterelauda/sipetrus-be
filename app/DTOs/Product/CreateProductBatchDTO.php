<?php

namespace App\DTOs\Product;

class CreateProductBatchDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $stock,
        public readonly string $expiredDate,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            stock: (int) $request->validated('stock'),
            expiredDate: (string) $request->validated('expired_date'),
        );
    }
}
