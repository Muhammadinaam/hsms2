<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingCancellation extends CommonModel
{
    public function booking()
    {
        return $this->belongsTo('\App\Booking');
    }
}
