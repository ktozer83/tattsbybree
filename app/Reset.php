<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reset extends Model
{
    protected $table = 'password_resets';
    
    protected $fillable = [
        'email', 'token'
    ];
    
    public $timestamps = false;
}