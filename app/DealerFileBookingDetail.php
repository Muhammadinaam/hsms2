<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerFileBookingDetail extends Model
{
    protected $fillable = [
        'file_id'
    ];

    public function file()
    {
        return $this->belongsTo('\App\File');
    }
}
