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
        public readonly float $costPrice,
        public readonly int $stock,
        public readonly ?string $barcode,
        public readonly string $category,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->validated('name'),
            price: (float) $request->validated('price'),
            costPrice: (float) $request->validated('cost_price'),
            stock: (int) $request->validated('stock'),
            barcode: $request->validated('barcode'),
            category: (string) $request->validated('category'),
        );
    }
}
