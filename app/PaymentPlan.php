<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends CommonModel
{
    protected $fillable = [
        'allotment_id',
        'starting_date',
        'amount',
        'number_of_payments',
        'days_between_payments',
    ];
}
