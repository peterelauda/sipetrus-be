<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashFlowSummarySheet implements FromCollection, WithHeadings
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
        return collect([
            [
                'Total Sales',
                $this->summary['total_sales']
            ],
            [
                'Total Expenses',
                $this->summary['total_expenses']
            ],
            [
                'Net Cash Flow',
                $this->summary['net_cash_flow']
            ],
        ]);
    }
}
