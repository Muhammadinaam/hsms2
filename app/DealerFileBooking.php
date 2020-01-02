<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerFileBooking extends Model
{
    public function dealerFileBookingDetails()
    {
        return $this->hasMany('\App\DealerFileBookingDetail');
    }

    public function dealerAmountReceivedAccount()
    {
        return $this->belongsTo('\App\AccountHead', 'dealer_amount_received_account_id');
    }
}
