<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerBookingDetail extends Model
{
    protected $fillable = [
        'property_file_id'
    ];

    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }
}
