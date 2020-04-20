<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }

    public function paymentPlanDetails()
    {
        return $this->hasMany('\App\PaymentPlanDetail');
    }
}
