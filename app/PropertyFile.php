<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyFile extends CommonModelWithStatuses
{
    public $all_statuses = [
        \App\Helpers\StatusesHelper::AVAILABLE,
        \App\Helpers\StatusesHelper::BOOKED,
        \App\Helpers\StatusesHelper::ALLOTTED,
        \App\Helpers\StatusesHelper::POSSESSED
    ];

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where(function($query) use ($search_term) {
                
                $query->orWhere('file_number', 'like', '%'.$search_term.'%');

                
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'File : ' . $this->file_number . 
        ', Plot Size: ' . $this->marlas . 
        ', Block: ' . ($this->block != null ? $this->block->name : '') . 
        ', Property Number: ' . $this->property_number . 
        ', Project: ' . $this->project->name . 
        ', Phase: ' . $this->phase->name;
    }

    public function getOpenOrOtherStatus()
    {
        if($this->holder_id == null && $this->dealer_id != null)
        {
            return 'open';
        }
        
        return $this->status;
    }

    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    public function booking()
    {
        return $this->hasOne('\App\Booking');
    }

    public function phase()
    {
        return $this->belongsTo('\App\Phase');
    }

    public function dealer()
    {
        return $this->belongsTo('\App\Person', 'dealer_id');
    }

    public function soldByDealer()
    {
        return $this->belongsTo('\App\Person', 'sold_by_dealer_id');
    }

    public function holder()
    {
        return $this->belongsTo('\App\Person', 'holder_id');
    }

    public function propertyType()
    {
        return $this->belongsTo('\App\PropertyType');
    }

    public function block()
    {
        return $this->belongsTo('\App\Block');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', \App\Helpers\StatusesHelper::AVAILABLE)
        ->whereNull('holder_id')
        ->whereNull('dealer_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', \App\Helpers\StatusesHelper::AVAILABLE)
        ->whereNull('holder_id')
        ->whereNotNull('dealer_id');
    }

    public function instalmentReceipts()
    {
        return $this->hasMany('\App\InstalmentReceipt');
    }

    public function getTotalInstallmentReceiptsAttribute()
    {
        $total = 0;

        foreach ($this->instalmentReceipts as $instalmentReceipt) {
            foreach ($instalmentReceipt->instalmentReceiptDetails as $instalmentReceiptDetail) {
                $total += $instalmentReceiptDetail->amount;
            }
        }

        return $total;
    }

    public function getCurrentHolderAttribute()
    {
        $dealer = $this->dealer;
        $holder = $this->holder;

        return $holder != null ? $holder : $dealer;
    }

    public function getCurrentDealerAttribute()
    {
        $sold_by_dealer = $this->soldByDealer;
        $dealer = $this->dealer;
        return $sold_by_dealer != null ? $sold_by_dealer : $dealer;
    }
}
