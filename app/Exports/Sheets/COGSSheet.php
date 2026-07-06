<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class COGSSheet implements FromCollection, WithHeadings
{
    public function __construct(
        private $items
    ) {}

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Product Name',
            'Quantity',
            'Cost Price',
            'Total Cost',
        ];
    }

    public function collection()
    {
        return collect($this->items)
            ->map(function ($item) {
                return [
                    $item->transaction->invoice_number,
                    $item->product->name,
                    $item->qty,
                    $item->cost_price,
                    $item->qty * $item->cost_price,
                ];
            });
    }
}
