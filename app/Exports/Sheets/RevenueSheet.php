<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueSheet implements FromCollection, WithHeadings
{
    public function __construct(
        private $transactions
    ) {}

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Transaction Date',
            'Payment Method',
            'Total Amount',
        ];
    }

    public function collection()
    {
        return collect($this->transactions)
            ->map(function ($item) {
                return [
                    $item->invoice_number,
                    $item->created_at,
                    $item->payment_method,
                    $item->total,
                ];
            });
    }
}
