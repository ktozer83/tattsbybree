<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $table = 'booking_status';
    
    public $timestamps = false;
    
    protected $fillable = [
        'can_book', 'message', 'no_bookings_until', 'slots_available'
    ];
    
}