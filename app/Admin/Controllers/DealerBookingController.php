<?php

namespace App\Admin\Controllers;

use App\DealerBooking;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DealerBookingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dealer Bookings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DealerBooking);

        $grid->column('date', __('Date'))->date('d-M-Y');
        $grid->column('dealer.text_for_select', __('Dealer'));
        $grid->column('dealer_amount_received', __('Dealer amount received'));
        $grid->column('dealerAmountReceivedAccount.text_for_select', __('Dealer amount received account'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(DealerBooking::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('dealer_id', __('Dealer id'));
        $show->field('dealer_amount_received', __('Dealer amount received'));
        $show->field('dealer_amount_received_account_id', __('Dealer amount received account id'));
        $show->field('created_by', __('Created by'));
        $show->field('updated_by', __('Updated by'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DealerBooking);
        $id = isset(request()->route()->parameters()['dealer_booking']) ? 
            request()->route()->parameters()['dealer_booking'] : null;
        $dealer_booking = \App\DealerBooking::find($id);

        $form->saving(function(Form $form) use ($dealer_booking) {
            if($dealer_booking != null )
            {
                foreach($dealer_booking->dealerBookingDetails as $detail)
                {
                    $property_file = $detail->propertyFile;
                    $property_file->dealer_id = null;
                    $property_file->save();
                }
            }
        });

        $form->saved(function (Form $form) {
            
            if( ! is_array(request()->dealerBookingDetails) || count(request()->dealerBookingDetails) == 0 )
            {
                throw new \Exception("Please select Property Files", 1);
            }
            
            foreach(request()->dealerBookingDetails as $detail)
            {
                if($detail['_remove_'] != '1')
                {
                    $property_file_id = $detail['property_file_id'];

                    $property_file = \App\PropertyFile::find($property_file_id);
                    $property_file->dealer_id = request()->dealer_id;
                    $property_file->save();
                }
            }

            self::postDealerBooking($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'dealer_id', 
            __('Dealer'), 
            'admin/people/create', 
            '\App\Person')
            ->rules('required');
        
        $form->decimal('dealer_amount_received', __('Dealer amount received'))
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'dealer_amount_received_account_id', 
            __('Dealer amount received account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Account Head in which amount received will be debited')
            ->rules('required');

        $form->hasMany('dealerBookingDetails', __('Files'), function (Form\NestedForm $form) use ($dealer_booking) {
            
            $property_file_where = ' dealer_id is null ';

            if($dealer_booking != null)
            {
                $details = $dealer_booking->DealerBookingDetails;
                if($details != null && count($details) > 0)
                {
                    $property_file_ids = $details->pluck('property_file_id')->toArray();
                    $property_file_where = '(' . $property_file_where . ' OR id in ('  . implode(',', $property_file_ids) . ') )';
                }
            }

            \App\Helpers\SelectHelper::buildAjaxSelect(
                $form, 
                'property_file_id', 
                __('Property File'), 
                '', 
                '\App\PropertyFile',
                $property_file_where)
                ->rules('required');

        })->mode('table');

        return $form;
    }

    public static function postDealerBooking(\App\DealerBooking $dealer_booking)
    {
        if($dealer_booking->id == null)
        {
            throw new \Exception("Dealer Booking not saved correctly", 1);   
        }

        $project_id = null;
        $phase_id = null;
        foreach($dealer_booking->dealerBookingDetails as $detail) 
        {
            // TODO - improve this
            $file_project_id = $detail->propertyFile->project_id; 
            $file_phase_id = $detail->propertyFile->phase_id; 
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

        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $dealer_booking->date, 
            \App\Ledger::DEALER_BOOKING, 
            $dealer_booking->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        // CASH / BANK DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $dealer_booking->dealer_amount_received_account_id,
            null,
            null,
            'Amount received from Dealer against Files Booking',
            $dealer_booking->dealer_amount_received
        );

        // DEALER ACCOUNT CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            $dealer_booking->dealer_id,
            null,
            'Amount received from Dealer against Files Booking',
            -$dealer_booking->dealer_amount_received
        );
    }
}
