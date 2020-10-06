<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPlanDetail extends Model
{
    protected $fillable = [
        'amount',
        'payment_plan_type_id',
        'number_of_payments',
        'days_between_each_payment',
        'starting_date',
    ];

    public function paymentPlanType()
    {
        return $this->belongsTo('\App\PaymentPlanType');
    }
}
