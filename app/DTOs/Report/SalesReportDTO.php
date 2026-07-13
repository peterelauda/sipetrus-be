<?php

namespace App\DTOs\Report;

use Illuminate\Support\Carbon;

class SalesReportDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly Carbon $startDate,
        public readonly Carbon $endDate,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            startDate: Carbon::parse(
                $request->validated('start_date')
            )->startOfDay(),

            endDate: Carbon::parse(
                $request->validated('end_date')
            )->endOfDay(),
        );
    }
}
