<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'account_type_id', 'get_email', 'banned'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function accountType()
    {
        return $this->belongsTo('App\AccountType');
    }
    
    public function quote()
    {
        return $this->belongsTo('App\Quotes');
    }
}
