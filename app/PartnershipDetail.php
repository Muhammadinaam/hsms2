<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnershipDetail extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'partner_id',
        'ratio'
    ];
}
