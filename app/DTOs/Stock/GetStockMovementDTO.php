<?php

namespace App\DTOs\Stock;

use Illuminate\Support\Carbon;

class GetStockMovementDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $page,
        public readonly int $limit,
        public readonly ?int $productId,
        public readonly ?string $type,
        public readonly ?Carbon $startDate,
        public readonly ?Carbon $endDate,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            page: (int) ($request->validated('page') ?? 1),

            limit: (int) ($request->validated('limit') ?? 10),

            productId: $request->validated('product_id'),

            type: $request->validated('type'),

            startDate: $request->validated('start_date')
            ? Carbon::parse($request->validated('start_date'))
            : null,

            endDate: $request->validated('end_date')
            ? Carbon::parse($request->validated('end_date'))
            : null,
        );
    }
}
