<?php

namespace App\Exports;

use App\Exports\Sheets\CashFlowSummarySheet;
use App\Exports\Sheets\ExpenseSheet;
use App\Exports\Sheets\SalesSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CashFlowExport implements WithMultipleSheets
{
    public function __construct(
        private array $data
    ) {}

    public function sheets(): array
    {
        return [
            'Summary'
            => new CashFlowSummarySheet(
                $this->data['summary']
            ),

            'Sales'
            => new SalesSheet(
                $this->data['sales_details']
            ),

            'Expenses'
            => new ExpenseSheet(
                $this->data['expense_details']
            ),
        ];
    }
}
