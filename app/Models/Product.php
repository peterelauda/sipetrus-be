<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'product_code' => $this->product_code,
            'barcode' => $this->barcode,
        ];
    }

    protected $guarded = [];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
