<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortfolioCategories extends Model
{
    protected $table = 'portfolio_categories';
    
    protected $fillable = [
        'category_name', 'hidden',
    ];
    
    public function images()
    {
        return $this->hasMany('App\PortfolioImages', 'category_id');
    }
}