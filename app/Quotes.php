<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    protected $table = 'quotes';
    
    protected $fillable = [
        'appointments_id', 'user_id', 'pending', 'name', 'phone_number', 'img_filename', 'budget_range', 'black_white', 'colour', 'quote_price', 'consultation', 'consultation_needed', 'consultation_date', 'consultation_time', 'consultation_confirmed', 'appointment_made', 'appointment_date', 'appointment_time', 'down_payment_cost', 'down_payment_paid'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function appointment_status()
    {
        return $this->belongsTo('App\AppointmentStatus');
    }
    
    public function images()
    {
        return $this->hasMany('App\UserImages', 'quote_id');
    }
    
    public function comments()
    {
        return $this->hasMany('App\QuoteComments', 'quote_id');
    }
}