<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfitLossSummarySheet implements FromCollection, WithHeadings
{
    public function __construct(
        private array $summary
    ) {}

    public function headings(): array
    {
        return [
            'Metric',
            'Amount',
        ];
    }

    public function collection()
    {
        $grossProfit =
            $this->summary['revenue']
            -
            $this->summary['cogs'];

        $netProfit =
            $grossProfit
            -
            $this->summary['expenses'];

        return collect([
            [
                'Revenue',
                $this->summary['revenue'],
            ],
            [
                'COGS',
                $this->summary['cogs'],
            ],
            [
                'Gross Profit',
                $grossProfit,
            ],
            [
                'Expenses',
                $this->summary['expenses'],
            ],
            [
                'Net Profit',
                $netProfit,
            ],
        ]);
    }
}
