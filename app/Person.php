<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                $query->orWhere('name', 'like', '%'.$search_term.'%')
                ->orWhere('cnic', 'like', '%'.$search_term.'%')
                ->orWhere('phone', 'like', '%'.$search_term.'%');
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'ID: ' . $this->system_id . 
        ', Name: ' . $this->name . 
        ', CNIC: ' . $this->cnic . 
        ', Type: ' . $this->person_type;
    }
}
