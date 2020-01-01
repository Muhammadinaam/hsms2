<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountHead extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where('name', 'like', '%'.$search_term.'%');

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Name: ' . $this->name . ', Type: ' . $this->type;
    }
}
