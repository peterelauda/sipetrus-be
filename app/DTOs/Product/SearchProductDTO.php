<?php

namespace App\DTOs\Product;

class SearchProductDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $keyword,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            keyword: (string) $request->validated('keyword'),
        );
    }
}
