<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                
                $query->orWhere('properties.name', 'like', '%'.$search_term.'%')

                ->orWhereHas('project', function($query) use ($search_term) {
                    $query->orWhere('short_name', 'like', '%'.$search_term.'%');
                })

                ->orWhereHas('phase', function($query) use ($search_term) {
                    $query->orWhere('short_name', 'like', '%'.$search_term.'%');
                })

                ->orWhereHas('propertyType', function($query) use ($search_term) {
                    $query->orWhere('short_name', 'like', '%'.$search_term.'%');
                });

            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Project: ' . $this->project->name . ', ' .
        'Phase: ' . $this->phase->name . ', ' .
        'Block: ' . $this->block->name . ', ' .
        'Type: ' . $this->propertyType->name . ', ' .
        'Name: ' . $this->name . ', ' .
        'Marlas: ' . $this->marlas . ', '
        ;
    }

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
