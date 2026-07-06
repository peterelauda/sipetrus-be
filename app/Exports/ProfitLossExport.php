<?php

namespace App\Exports;

use App\Exports\Sheets\COGSSheet;
use App\Exports\Sheets\ExpenseSheet;
use App\Exports\Sheets\ProfitLossSummarySheet;
use App\Exports\Sheets\RevenueSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProfitLossExport implements WithMultipleSheets
{
    public function __construct(
        private array $data
    ) {}

    public function sheets(): array
    {
        return [
            'Summary'
            => new ProfitLossSummarySheet(
                $this->data['summary']
            ),

            'Revenue'
            => new RevenueSheet(
                $this->data['sales_details']
            ),

            'COGS'
            => new COGSSheet(
                $this->data['cogs_details']
            ),

            'Expenses'
            => new ExpenseSheet(
                $this->data['expense_details']
            ),
        ];
    }
}
