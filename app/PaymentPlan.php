<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends CommonModel
{
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            \App\PaymentPlanSchedule::where('payment_plan_id', $model->id)->delete();
        });
    }

    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }

    public function paymentPlanDetails()
    {
        return $this->hasMany('\App\PaymentPlanDetail');
    }
}
