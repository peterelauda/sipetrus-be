<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'invoice_number' => $this->invoice_number,
            'total' => (int) $this->total,
            'paid_amount' => (int) $this->paid_amount,
            'change_amount' => (int) $this->change_amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'id' => $this->id,
        ];
    }
}
