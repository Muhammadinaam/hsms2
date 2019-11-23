<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends CommonModel
{
    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                $query->orWhere('id', 'like', '%'.$search_term.'%')
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
            'Id: ' . $this->id . ', ' .
            'Cust. Name: ' . $this->customer->name . ', ' .
            'CNIC: ' . $this->customer->cnic . ', ' .
            'Phone: ' . $this->customer->phone . ', ' .
            'Cust. Amount: ' . $this->customer_amount_received . ', ' .
            'Agent Comm: ' . $this->agent_commission_amount;
    }

    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }

    public function customer()
    {
        return $this->belongsTo('\App\person', 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo('\App\person', 'agent_id');
    }
}
