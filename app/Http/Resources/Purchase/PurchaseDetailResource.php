<?php

namespace App\Http\Resources\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'purchase_date' => $this->purchase_date,
            'total_cost' => (float) $this->total_cost,
            'supplier' => [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
                'phone' => $this->supplier->phone,
                'email' => $this->supplier->email,
                'address' => $this->supplier->address,
            ],
            'items' => $this->items->map(function ($item) {

                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'batch_number' => $item->batch_number,
                    'qty' => $item->qty,
                    'cost_price' => (float) $item->cost_price,
                    'subtotal' => (float) $item->subtotal,
                    'expired_date' => $item->expired_date,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
