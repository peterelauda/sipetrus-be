<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_sales' => (float) $this['total_sales'],
            'total_transactions' => (int) $this['total_transactions'],
            'total_profit' => (float) $this['total_profit'],
            'items_sold' => (int) $this['items_sold'],
        ];
    }
}
