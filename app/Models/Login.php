<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'login';

    // Set email as primary key
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string'; // since email is a string key
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
