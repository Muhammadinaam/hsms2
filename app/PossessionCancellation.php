<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PossessionCancellation extends CommonModel
{
    public function possession()
    {
        return $this->belongsTo('\App\Possession');
    }
}
