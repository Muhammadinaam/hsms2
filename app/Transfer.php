<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends CommonModel
{
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $property = \App\Property::find($model->property_id);
            $model->transferred_from_id = $property->holder_id;
            $property->holder_id = $model->transferred_to_id;
            $property->save();
        });

        parent::boot();
        self::updating(function ($model) {
            $property = \App\Property::find($model->property_id);
            $property->holder_id = $model->transferred_to_id;
            $property->save();
        });
    }

    public function property()
    {
        return $this->belongsTo('\App\Property');
    }

    public function transferredTo()
    {
        return $this->belongsTo('\App\person', 'transferred_to_id');
    }

    public function transferredFrom()
    {
        return $this->belongsTo('\App\person', 'transferred_from_id');
    }
}
