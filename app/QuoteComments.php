<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteComments extends Model
{
    protected $table = 'quote_comments';
    
    protected $fillable = [
        'quote_id', 'user_name', 'comment'
    ];
    
    public function quote()
    {
        return $this->hasOne('App\Quotes');
    }
}