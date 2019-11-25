<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allotment extends Model
{
    public function paymentPlans()
    {
        return $this->hasMany('\App\PaymentPlan');
    }
}
