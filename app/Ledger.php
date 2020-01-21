<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    const LAND_PURCHASE = 'Land Purchase';
    const DEALER_BOOKING = 'Dealer Booking';
    const DEALER_BOOKING_RETURN = 'Dealer Booking Return';
    const CUSTOMER_BOOKING = 'Customer Booking';
    const JOURNAL_VOUCHER = 'Journal Voucher';

    public function ledgerEntries()
    {
        return $this->hasMany('\App\LedgerEntry');
    }

    public static function insertOrUpdateLedger(
        $project_id, 
        $phase_id, 
        $date, 
        $entry_type, 
        $entry_id)
    {
        $ledger = self::where('entry_type', $entry_type)
            ->where('entry_id', $entry_id)
            ->first();

        if($ledger == null)
        {
            $ledger = new \App\Ledger;
            $ledger->created_by = \Auth::guard('admin')->user()->id;
        }
        else
        {
            $ledger->updated_by = \Auth::guard('admin')->user()->id;
        }

        $ledger->project_id = $project_id;
        $ledger->phase_id = $phase_id;
        $ledger->date = $date;
        $ledger->entry_type = $entry_type;
        $ledger->entry_id = $entry_id;

        $ledger->save();

        return $ledger->id;
    }

    public static function insertOrUpdateLedgerEntries(
        $ledger_id, 
        $account_head_id, 
        $person_id, 
        $property_file_id,
        $description,
        $amount)
    {
        $ledger_entry = new \App\LedgerEntry;

        $ledger_entry->ledger_id = $ledger_id;
        $ledger_entry->account_head_id = $account_head_id;
        $ledger_entry->person_id = $person_id;
        $ledger_entry->property_file_id = $property_file_id;
        $ledger_entry->description = $description;
        $ledger_entry->amount = $amount;

        $ledger_entry->save();

        return $ledger_entry->id;
    }
}
