<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable, SoftDeletes;

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'barcode' => $this->barcode,
        ];
    }

    protected $guarded = [];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    protected $casts = [
        'price' => 'float',
        'cost_price' => 'float',
    ];
}
