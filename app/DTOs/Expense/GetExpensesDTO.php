<?php

namespace App\DTOs\Expense;

class GetExpensesDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $category,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly ?int $perPage,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            category: $request->input('category'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            perPage: $request->input('per_page')
                ? (int) $request->input('per_page')
                : 10,
        );
    }
}
