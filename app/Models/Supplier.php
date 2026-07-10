<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    /**
     * Store owner.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
