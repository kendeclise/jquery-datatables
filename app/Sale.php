<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer'
    ];

    /* Relations */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_sales')->withPivot('quantity');
    }
}
