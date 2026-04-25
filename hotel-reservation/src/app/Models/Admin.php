<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
