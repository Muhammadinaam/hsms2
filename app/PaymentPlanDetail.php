<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPlanDetail extends Model
{
    protected $fillable = [
        'amount',
        'description',
        'number_of_payments',
        'days_between_each_payment',
        'starting_date',
    ];
}
