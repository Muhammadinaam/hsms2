<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends CommonModelWithStatuses
{
    protected $appends = ['text_for_select'];

    public $all_statuses = [
        \App\Helpers\StatusesHelper::BOOKED,
        \App\Helpers\StatusesHelper::ALLOTTED,
        \App\Helpers\StatusesHelper::POSSESSED
    ];
    public $effected_relations = ['propertyFile'];

    public const BOOKING_TYPE_CASH = 'Cash';
    public const BOOKING_TYPE_INSTALLMENT = 'Installment';

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {

                $query->orWhereHas('propertyFile', function($query) use ($search_term) {
                    $query->orWhere('file_number', 'like', '%'.$search_term.'%');
                })
                
                ->orWhereHas('customer', function($query) use ($search_term) {
                    $query->orWhere('name', 'like', '%'.$search_term.'%')
                    ->orWhere('cnic', 'like', '%'.$search_term.'%')
                    ->orWhere('phone', 'like', '%'.$search_term.'%');
                });
            });


        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 
            'File: ' . $this->propertyFile->file_number . ', ' .
            'Cust. Name: ' . $this->customer->name . ', ' .
            'CNIC: ' . $this->customer->cnic . ', ' .
            'Phone: ' . $this->customer->phone . ', ';
    }

    public function customer()
    {
        return $this->belongsTo('\App\Person', 'customer_id');
    }

    public function dealer()
    {
        return $this->belongsTo('\App\Person', 'dealer_id');
    }

    public function propertyFile()
    {
        return $this->belongsTo('\App\PropertyFile');
    }
}
