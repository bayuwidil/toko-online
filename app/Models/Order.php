<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

protected $fillable = [
    'user_id',
    'order_number',

    'shipping_name',
    'shipping_phone',
    'shipping_address',
    'shipping_subdistrict',
    'shipping_district',
    'shipping_city',
    'shipping_province',
    'shipping_postal_code',
    'shipping_destination_id',

    'shipping_weight',
    'shipping_courier',
    'shipping_service',

    'subtotal',
    'shipping_cost',
    'grand_total',

    'status',
    'payment_status',

    'snap_token',
];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        // 1 order punya banyak barang
        return $this->hasMany(OrderItem::class); 
    }
    
}
