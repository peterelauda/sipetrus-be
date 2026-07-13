<?php

namespace App\DTOs\Purchase;

class GetPurchasesDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?int $supplierId,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly int $perPage,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            supplierId: $request->validated('supplier_id'),
            startDate: $request->validated('start_date'),
            endDate: $request->validated('end_date'),
            perPage: (int) ($request->validated('per_page') ?? 10),
        );
    }
}
