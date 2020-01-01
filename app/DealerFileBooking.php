<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerFileBooking extends Model
{
    public function dealerFileBookingDetails()
    {
        return $this->hasMany('\App\DealerFileBookingDetail');
    }
}
