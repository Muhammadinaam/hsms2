<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends CommonModel
{
    public function journalVoucherDetails()
    {
        return $this->hasMany('\App\JournalVoucherDetail');
    }

    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }
}
