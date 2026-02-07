<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_code',
        'customer_name',
        'product_id',
        'order_date',
        'total_amount',
        'status',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
