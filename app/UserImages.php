<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    protected $table = 'user_images';
    
    protected $fillable = [
        'quote_id', 'filename', 'user'
    ];
    
    public function quote()
    {
        return $this->hasOne('App\Quotes');
    }
}