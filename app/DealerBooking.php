<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerBooking extends CommonModel
{
    public function dealerBookingDetails()
    {
        return $this->hasMany('\App\DealerBookingDetail');
    }

    public function dealerAmountReceivedAccount()
    {
        return $this->belongsTo('\App\AccountHead', 'dealer_amount_received_account_id');
    }

    public function dealer()
    {
        return $this->belongsTo('\App\Person', 'dealer_id');
    }
}
