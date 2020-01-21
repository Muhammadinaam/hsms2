<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerBookingReturn extends CommonModel
{
    public function dealerBookingReturnDetails()
    {
        return $this->hasMany('\App\DealerBookingReturnDetail');
    }

    public function dealerAmountReturnedAccount()
    {
        return $this->belongsTo('\App\AccountHead', 'dealer_amount_returned_account_id');
    }

    public function dealer()
    {
        return $this->belongsTo('\App\Person', 'dealer_id');
    }
}
