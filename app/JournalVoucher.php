<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends CommonModel
{
    public function journalVoucherDetails()
    {
        return $this->hasMany('\App\JournalVoucherDetail');
    }
}
