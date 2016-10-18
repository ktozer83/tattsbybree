<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortfolioImages extends Model
{
    protected $table = 'portfolio_images';
    
    protected $fillable = [
        'filename', 'hidden', 'featured', 'cover', 'image_title', 'image_caption'
    ];
    
    public function category()
    {
        return $this->belongsTo('App\PortfolioCategories', 'category_id');
    }
}