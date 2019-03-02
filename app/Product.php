<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'buy_price', 'sell_price', 'stock', 'category_id'
    ];

    /* Relations */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'products_sales')->withPivot('quantity');
    }
}
