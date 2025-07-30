<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        // 'product_id',
        'product_name',
        'qty',
        'price',
        'subtotal'
    ];


    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    // }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'order_id'); // or 'order_id' if your order table uses that as PK
    }
}
