<?php

namespace App\DTOs\Supplier;

class GetSuppliersDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $keyword,
        public readonly int $perPage,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            keyword: $request->validated('keyword'),
            perPage: (int) ($request->validated('per_page') ?? 10),
        );
    }
}
