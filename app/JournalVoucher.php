<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends Model
{
    public function journalVoucherDetails()
    {
        return $this->hasMany('\App\JournalVoucherDetail');
    }
}
