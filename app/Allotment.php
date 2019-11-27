<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allotment extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {

                $query->orWhereHas('property', function($query) use ($search_term) {
                    $query->orWhere('properties.name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('property.project', function($query) use ($search_term) {
                    $query->orWhere('projects.name', 'like', '%'.$search_term.'%')
                        ->orWhere('projects.short_name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('property.phase', function($query) use ($search_term) {
                    $query->orWhere('phases.name', 'like', '%'.$search_term.'%')
                        ->orWhere('phases.short_name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('property.block', function($query) use ($search_term) {
                    $query->orWhere('blocks.name', 'like', '%'.$search_term.'%')
                        ->orWhere('blocks.short_name', 'like', '%'.$search_term.'%');
                });

                $query->orWhereHas('booking.customer', function($query) use ($search_term) {
                    $query->orWhere('name', 'like', '%'.$search_term.'%');
                });
            });


        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 
            'Booking: ' . $this->booking->booking_number . ', ' .
            'Cust. Name: ' . $this->booking->customer->name . ', ' .
            'CNIC: ' . $this->booking->customer->cnic;
    }

    public function paymentPlans()
    {
        return $this->hasMany('\App\PaymentPlan');
    }

    public function property()
    {
        return $this->belongsTo('\App\Property');
    }

    public function booking()
    {
        return $this->belongsTo('\App\Booking');
    }
}
