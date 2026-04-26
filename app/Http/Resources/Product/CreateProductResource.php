<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateProductResource extends JsonResource
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
            'barcode' => $this->barcode,
            'name' => $this->name,
            'price' => (float) $this->price,
            'cost_price' => (float) $this->cost_price,
            'stock' => (int) $this->stock,
            'category' => (string) $this->category,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'id' => $this->id,
        ];
    }
}
