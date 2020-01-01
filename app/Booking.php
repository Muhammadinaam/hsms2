<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends CommonModelWithStatuses
{
    protected $appends = ['text_for_select', 'booking_number'];

    public $all_statuses = [
        \App\Helpers\StatusesHelper::BOOKED,
        \App\Helpers\StatusesHelper::ALLOTTED,
        \App\Helpers\StatusesHelper::POSSESSED
    ];

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {

                $query->orWhere('booking_sequence_number', 'like', '%'.$search_term.'%')

                ->orWhereHas('project', function($query) use ($search_term) {
                    $query->orWhere('short_name', 'like', '%'.$search_term.'%');
                })

                ->orWhereHas('phase', function($query) use ($search_term) {
                    $query->orWhere('short_name', 'like', '%'.$search_term.'%');
                })

                ->orWhereHas('propertyType', function($query) use ($search_term) {
                    $query->orWhere('short_name', 'like', '%'.$search_term.'%');
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
            'Booking: ' . $this->booking_number . ', ' .
            'Cust. Name: ' . $this->customer->name . ', ' .
            'CNIC: ' . $this->customer->cnic . ', ' .
            'Phone: ' . $this->customer->phone . ', ' .
            'Cust. Amount: ' . $this->customer_amount_received . ', ' .
            'Dealer Comm: ' . $this->dealer_commission_amount;
    }

    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }

    public function propertyType()
    {
        return $this->belongsTo('\App\PropertyType');
    }

    public function customer()
    {
        return $this->belongsTo('\App\person', 'customer_id');
    }

    public function dealer()
    {
        return $this->belongsTo('\App\person', 'dealer_id');
    }

    public function getBookingNumberAttribute ()
    {
        return $this->project->short_name . '/' .
            $this->phase->short_name . '/' .
            $this->propertyType->short_name . '/' .
            $this->booking_sequence_number;
    }
}
