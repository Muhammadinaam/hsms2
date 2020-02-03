<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allotment extends CommonModelWithStatuses
{
    public $all_statuses = [
        \App\Helpers\StatusesHelper::ALLOTTED,
        \App\Helpers\StatusesHelper::POSSESSED
    ];
    public $effected_relations = ['booking'];

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {

                $query->orWhereHas('booking.customer', function($query) use ($search_term) {
                    $query->orWhere('name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('booking.propertyFile', function($query) use ($search_term) {
                    $query->orWhere('file_number', 'like', '%'.$search_term.'%');
                });
            });


        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 
            'Allotment Id: ' . $this->id . ', ' .
            'Property File: ' . $this->booking->propertyFile->file_number . ', ' .
            'Booking: ' . $this->booking->booking_number . ', ' .
            'Cust. Name: ' . $this->booking->customer->name . ', ' .
            'CNIC: ' . $this->booking->customer->cnic;
    }

    public function block()
    {
        return $this->belongsTo('\App\Block');
    }

    public function booking()
    {
        return $this->belongsTo('\App\Booking');
    }
}
