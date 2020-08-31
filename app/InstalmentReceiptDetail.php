<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstalmentReceiptDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'amount',
        'payment_plan_type_id'
    ];
}
