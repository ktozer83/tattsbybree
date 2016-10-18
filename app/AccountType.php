<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = 'account_type';
    
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}