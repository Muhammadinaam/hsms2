<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Possession extends CommonModelWithStatuses
{
    public $all_statuses = [
        \App\Helpers\StatusesHelper::POSSESSED
    ];
    public $effected_relations = ['allotment'];

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {

                $query->orWhereHas('allotment.property', function($query) use ($search_term) {
                    $query->orWhere('properties.name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('allotment.property.project', function($query) use ($search_term) {
                    $query->orWhere('projects.name', 'like', '%'.$search_term.'%')
                        ->orWhere('projects.short_name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('allotment.property.phase', function($query) use ($search_term) {
                    $query->orWhere('phases.name', 'like', '%'.$search_term.'%')
                        ->orWhere('phases.short_name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('allotment.property.block', function($query) use ($search_term) {
                    $query->orWhere('blocks.name', 'like', '%'.$search_term.'%')
                        ->orWhere('blocks.short_name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('allotment.booking.customer', function($query) use ($search_term) {
                    $query->orWhere('name', 'like', '%'.$search_term.'%');
                });
            });


        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 
            'Possession: ' . $this->id . ', ' .
            'Allotment: ' . $this->id . ', ' .
            'Booking: ' . $this->allotment->booking->booking_number . ', ' .
            'Cust. Name: ' . $this->allotment->booking->customer->name . ', ' .
            'CNIC: ' . $this->allotment->booking->customer->cnic;
    }

    public function allotment()
    {
        return $this->belongsTo('\App\Allotment');
    }
}
