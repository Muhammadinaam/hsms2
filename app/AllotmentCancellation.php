<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllotmentCancellation extends CommonModel
{
    

    public function allotment()
    {
        return $this->belongsTo('\App\Allotment');
    }
}
