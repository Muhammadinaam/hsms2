<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonModel extends Model
{
    protected $appends = ['text_for_select'];

    public function searchForSelect($search_term, $where_clauses)
    {
        if($where_clauses == '') 
        {
            return $this;
        }

        $data = $this
            ->where(function($query) use ($search_term) {
                $query->whereRaw($where_clauses);
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Id: ' . $this->id;
    }
}
