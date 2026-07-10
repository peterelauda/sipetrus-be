<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'batch_number',
        'qty',
        'cost_price',
        'subtotal',
        'expired_date',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'expired_date' => 'date',
    ];

    /**
     * Purchase.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
