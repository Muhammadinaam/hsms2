<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                $query->orWhere('name', 'like', '%'.$search_term.'%')
                ->orWhere('short_name', 'like', '%'.$search_term.'%');
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Name: ' . $this->name . ', ' . 'Short Name: ' . $this->short_name;
    }
}
