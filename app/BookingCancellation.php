<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingCancellation extends Model
{
    public function booking()
    {
        return $this->belongsTo('\App\Booking');
    }
}
