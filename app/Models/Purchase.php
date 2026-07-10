<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'supplier_id',
        'invoice_number',
        'purchase_date',
        'total_cost',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Store.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Purchase items.
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
