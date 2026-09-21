<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'weight',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        // Mengambil data spesifik produk untuk item ini
        return $this->belongsTo(Product::class); 
    }
}
