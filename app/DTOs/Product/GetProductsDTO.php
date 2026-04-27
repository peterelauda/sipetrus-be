<?php

namespace App\DTOs\Product;

class GetProductsDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $barcode,
        public readonly ?int $perPage,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            barcode: (string) $request->validated('barcode'),
            perPage: (int) $request->validated('per_page'),
        );
    }
}
