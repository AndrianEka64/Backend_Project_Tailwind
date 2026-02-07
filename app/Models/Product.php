<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'slug',
        'category',
        'price',
        'stock',
        'status',
        'image',
        'description',
    ];
    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
}
