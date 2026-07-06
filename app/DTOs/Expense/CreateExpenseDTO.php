<?php

namespace App\DTOs\Expense;

class CreateExpenseDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $category,
        public readonly ?string $description,
        public readonly float $amount,
        public readonly string $expenseDate,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            category: (string) $request->validated('category'),
            description: $request->validated('description'),
            amount: (float) $request->validated('amount'),
            expenseDate: (string) $request->validated('expense_date'),
        );
    }
}
