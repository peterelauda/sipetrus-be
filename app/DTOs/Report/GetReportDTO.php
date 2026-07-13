<?php

namespace App\DTOs\Report;

class GetReportDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $startDate,
        public readonly ?string $endDate,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            startDate: $request->validated('start_date'),
            endDate: $request->validated('end_date'),
        );
    }
}
