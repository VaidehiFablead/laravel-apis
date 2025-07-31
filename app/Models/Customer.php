<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customer";

    protected $primaryKey = 'customer_id'; 

   

    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'gender',
        'city',
        'address',
        'contact_info'
    ];

     
}
