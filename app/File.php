<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends CommonModelWithStatuses
{
    public $all_statuses = [
        \App\Helpers\StatusesHelper::AVAILABLE,
        \App\Helpers\StatusesHelper::BOOKED,
        \App\Helpers\StatusesHelper::ALLOTTED,
        \App\Helpers\StatusesHelper::POSSESSED
    ];

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                
                $query->orWhere('file_number', 'like', '%'.$search_term.'%');

                
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'File Number: ' . $this->file_number . 
        ', Marlas: ' . $this->marlas;
    }

    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }

    public function dealer()
    {
        return $this->belongsTo('\App\Person', 'dealer_id');
    }

    public function holder()
    {
        return $this->belongsTo('\App\Person', 'holder_id');
    }

    public function property()
    {
        return $this->belongsTo('\App\Property');
    }
}
