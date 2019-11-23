<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                $queryorWhere('name', 'like', '%'.$search_term.'%')
                ->orWhere('cnic', 'like', '%'.$search_term.'%')
                ->orWhere('phone', 'like', '%'.$search_term.'%');
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Name: ' . $this->name . ', ' . 'CNIC: ' . $this->cnic . ', Phone: ' . $this->phone . ', Type: ' . $this->person_type;
    }
}
