<?php

namespace App\DTOs\Product;

class AdjustStockDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $type,
        public readonly int $qty,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            type: $request->validated('type'),
            qty: (int) $request->validated('qty'),
        );
    }
}
