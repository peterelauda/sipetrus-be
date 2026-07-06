<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItemBatch extends Model
{
    protected $fillable = [
        'transaction_item_id',
        'product_batch_id',
        'qty',
    ];

    public function transactionItem()
    {
        return $this->belongsTo(
            TransactionItem::class
        );
    }

    public function productBatch()
    {
        return $this->belongsTo(
            ProductBatch::class
        );
    }
}
