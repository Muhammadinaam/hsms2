<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyInventoryAdjustment extends CommonModel
{
    public function propertyType()
    {
        return $this->belongsTo('\App\PropertyType');
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
