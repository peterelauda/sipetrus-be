<?php

namespace App\DTOs\Transaction;

class GetTransactionDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $page,
        public readonly int $limit,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly ?string $paymentMethod,
    ) {
    }

    /**
     * Create DTO instance from the validated Request.
     */
    public static function fromRequest($request): self
    {
        return new self(
            page: (int) $request->input('page', 1),
            limit: (int) $request->input('limit', 10),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            paymentMethod: $request->validated('payment_method'),
        );
    }
}
