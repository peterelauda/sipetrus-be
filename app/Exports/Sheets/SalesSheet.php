<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesSheet implements FromCollection, WithHeadings
{
    public function __construct(
        private $sales
    ) {}

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Payment Method',
            'Total Amount',
            'Transaction Date',
        ];
    }

    public function collection()
    {
        return collect($this->sales)
            ->map(function ($sale) {
                return [
                    $sale->invoice_number,
                    $sale->payment_method,
                    $sale->total,
                    $sale->created_at,
                ];
            });
    }
}
