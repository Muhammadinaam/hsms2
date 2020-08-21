<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstalmentReceipt extends CommonModel
{
    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }
}
