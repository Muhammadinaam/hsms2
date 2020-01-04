<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    const DEALER_BOOKING = 'Dealer Booking';

    public function ledgerEntries()
    {
        return $this->hasMany('\App\LedgerEntry');
    }

    private static function insertOrUpdateLedger(
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

    private static function insertOrUpdateLedgerEntries(
        $ledger_id, 
        $account_head_id, 
        $person_id, 
        $file_id,
        $description,
        $amount)
    {
        $ledger_entry = new \App\LedgerEntry;

        $ledger_entry->ledger_id = $ledger_id;
        $ledger_entry->account_head_id = $account_head_id;
        $ledger_entry->person_id = $person_id;
        $ledger_entry->file_id = $file_id;
        $ledger_entry->description = $description;
        $ledger_entry->amount = $amount;

        $ledger_entry->save();

        return $ledger_entry->id;
    }

    public static function postDealerFileBooking(\App\DealerFileBooking $dealer_file_booking)
    {
        if($dealer_file_booking->id == null)
        {
            throw new \Exception("Dealer File Booking not saved correctly", 1);   
        }

        $project_id = null;
        $phase_id = null;
        foreach($dealer_file_booking->dealerFileBookingDetails as $detail) 
        {
            // TODO - improve this
            $file_project_id = $detail->file->project_id; 
            $file_phase_id = $detail->file->phase_id; 
            // TODO - improve this

            if($project_id == null)
            {
                
                $project_id = $file_project_id;
            }
            else
            {
                if($project_id != $file_project_id) 
                {
                    throw new \Exception("All Files should be related to same Project", 1);
                }
            }

            if($phase_id == null)
            {
                
                $phase_id = $file_phase_id;
            }
            else
            {
                if($phase_id != $file_phase_id) 
                {
                    throw new \Exception("All Files should be related to same Phase", 1);
                }
            }
        }

        $ledger_id = self::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $dealer_file_booking->date, 
            Ledger::DEALER_BOOKING, 
            $dealer_file_booking->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        // CASH / BANK DEBIT
        self::insertOrUpdateLedgerEntries(
            $ledger_id,
            $dealer_file_booking->dealer_amount_received_account_id,
            null,
            null,
            'Amount received from Dealer against Files Booking',
            $dealer_file_booking->dealer_amount_received
        );

        // DEALER ACCOUNT CREDIT
        self::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            $dealer_file_booking->dealer_id,
            null,
            'Amount received from Dealer against Files Booking',
            -$dealer_file_booking->dealer_amount_received
        );
    }
}
