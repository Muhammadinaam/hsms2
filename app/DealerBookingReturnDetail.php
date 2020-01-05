<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerBookingReturnDetail extends Model
{
    protected $fillable = [
        'property_file_id'
    ];

    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }
}
