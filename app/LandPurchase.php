<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandPurchase extends CommonModel
{
    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }

    public function creditAccount()
    {
        return $this->belongsTo('\App\AccountHead', 'credit_account_id');
    }
}
