<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends CommonModelWithStatuses
{
    public $all_statuses = [
        \App\Helpers\StatusesHelper::AVAILABLE,
        \App\Helpers\StatusesHelper::ALLOTTED,
        \App\Helpers\StatusesHelper::POSSESSED
    ];

    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }
}
