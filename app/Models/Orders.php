<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $primaryKey = 'order_id';
    protected $fillable = [
        'customer_id',
        'subtotal' // now only this, product info is removed
    ];


    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class, 'customer_id');
    // }

    // public function orderItem(){
    //     return $this->hasMany(OrderItem::class,'order_id');
    // }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
