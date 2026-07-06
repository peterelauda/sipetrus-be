<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseSheet implements FromCollection, WithHeadings
{
    public function __construct(
        private $expenses
    ) {}

    public function headings(): array
    {
        return [
            'Expense Date',
            'Category',
            'Description',
            'Amount',
        ];
    }

    public function collection()
    {
        return collect($this->expenses)
            ->map(function ($expense) {
                return [
                    $expense->expense_date,
                    $expense->category,
                    $expense->description,
                    $expense->amount,
                ];
            });
    }
}
