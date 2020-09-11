<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalVoucherDetail extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    public function accountHead()
    {
        return $this->belongsTo('\App\AccountHead');
    }

    public function person()
    {
        return $this->belongsTo('\App\Person');
    }

    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }
}
