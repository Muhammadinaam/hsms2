<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPlanType extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                $query->orWhere('name', 'like', '%'.$search_term.'%');                
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return $this->name;
    }
}
