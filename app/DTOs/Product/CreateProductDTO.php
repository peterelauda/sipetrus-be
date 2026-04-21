<?php

namespace App\DTOs\Product;

class CreateProductDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly int $stock,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->validated('name'),
            price: (float) $request->validated('price'),
            stock: (int) $request->validated('stock'),
        );
    }
}
