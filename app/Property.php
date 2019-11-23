<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }

    public function block()
    {
        return $this->belongsTo('\App\Block');
    }

    public function propertyType()
    {
        return $this->belongsTo('\App\PropertyType');
    }
}
