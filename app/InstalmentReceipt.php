<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstalmentReceipt extends CommonModel
{
    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }

    public function instalmentReceiptDetails()
    {
        return $this->hasMany('\App\InstalmentReceiptDetail');
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
