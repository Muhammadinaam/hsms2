<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends CommonModel
{
    public const PERSON_TYPE_DEALER = 'Dealer';
    public const PERSON_TYPE_CUSTOMER = 'Customer';
    public const PERSON_TYPE_SUPPLIER = 'Supplier';
    public const PERSON_TYPE_EMPLOYEE = 'Employee';

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                $query->orWhere('name', 'like', '%'.$search_term.'%')
                ->orWhere('cnic', 'like', '%'.$search_term.'%')
                ->orWhere('business_name', 'like', '%'.$search_term.'%')
                ->orWhere('phone', 'like', '%'.$search_term.'%');
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'ID: ' . $this->system_id . 
        ( $this->business_name != '' ? ', Business Name: ' . $this->business_name : '' ) .
        ', Name: ' . $this->name .
        ', CNIC: ' . $this->cnic . 
        ', Type: ' . $this->person_type;
    }
}
