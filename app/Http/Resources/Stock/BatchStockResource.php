<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'batch_number' => $this->batch_number,
            'stock' => $this->stock,
            'expired_date' => $this->expired_date?->format('Y-m-d'),
            'days_remaining' => now()->diffInDays(
                $this->expired_date,
                false
            ),
        ];
    }
}
