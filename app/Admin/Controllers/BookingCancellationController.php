<?php

namespace App\Admin\Controllers;

use App\BookingCancellation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\UpdateHelpers;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class BookingCancellationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Booking Cancellations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BookingCancellation);

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date of cancellation'));
        $grid->column('cancellation_reason', __('Cancellation Reason'));
        $grid->column('booking.booking_number', __('Booking Number'));

        $grid->column('customer_amount_returned', __('Customer amount returned'));
        $grid->column('dealer_commission_to_be_returned', __('Dealer Commission To be Returned'));

        \App\Helpers\GeneralHelpers::setGridRowActions($grid, false, true, true, true);

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
        $show = new Show(BookingCancellation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date of cancellation'));
        $show->field('booking_id', __('Booking id'));
        $show->field('customer_amount_returned', __('Customer amount returned'));
        $show->field('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $show->field('dealer_commission_to_be_returned', __('Dealer commission to be returned'));
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
        $form = new Form(new BookingCancellation);
        $id = isset(request()->route()->parameters()['booking_cancellation']) ? 
            request()->route()->parameters()['booking_cancellation'] : null;
        $booking_cancellation = \App\BookingCancellation::find($id);

        $form->saving(function (Form $form) use ($id, $booking_cancellation) {
            
            if($booking_cancellation != null && $booking_cancellation->booking != null)
            {
                $old_booking = $booking_cancellation->booking;
                $ret = $old_booking->unsetCancelledStatus();
                $old_property_file = $old_booking->propertyFile;
                $old_property_file->sold_by_dealer_id = $old_booking->propertyFile->dealer_id;
                $old_property_file->dealer_id = null;
                $old_property_file->holder_id = $old_booking->customer_id;
                $old_property_file->save();
                if ($ret instanceof Response) {
                    return $ret;
                }
            }

            $new_booking = \App\Booking::find($form->booking_id);
            $new_property_file = $new_booking->propertyFile;
            $new_property_file->dealer_id = $new_property_file->sold_by_dealer_id;
            $new_property_file->sold_by_dealer_id = null;
            $new_property_file->holder_id = null;
            $new_property_file->save();
            $ret = $new_booking->setCancelledStatus();
            if ($ret instanceof Response) {
                return $ret;
            }

        });


        $form->saved(function(Form $form) {

            self::postToLedger($form->model());
        });


        $booking_where = 'status = \''. \App\Helpers\StatusesHelper::BOOKED .'\'';
        $booking_where .=  $id != null ? ' OR bookings.id = ' . $booking_cancellation->booking_id : '';

        $form->divider('Booking Cancellation Information');

        $form->date('date', __('Date of cancellation'))->default(date('Y-m-d H:i:s'));
        $form->text('cancellation_reason', __('Cancellation Reason'));

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'booking_id', 
            __('Booking'), 
            '', 
            '\App\Booking')
            ->rules('required');

        $form->divider('Customer Amount Returned');

        $form->decimal('customer_amount_returned', __('Customer amount returned'))
        ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'customer_amount_returned_account_id', 
            __('Customer amount returned account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Cash or Bank Account from which amount is being returned')
            ->rules('required');

        $form->divider('Customer Amount Returned');

        $form->decimal('dealer_commission_to_be_returned', __('Dealer commission to be returned'));

        return $form;
    }

    public static function postToLedger(\App\BookingCancellation $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Booking Cancellation not saved correctly", 1);   
        }

        $project_id = $model->booking->propertyFile->project_id;
        $phase_id = $model->booking->propertyFile->phase_id;
        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $model->date, 
            \App\Ledger::CUSTOMER_BOOKING_CANCELLATION, 
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        $booking = $model->booking;
        $file = \App\PropertyFile::find($booking->property_file_id);
        $sale_price = $booking->booking_type == \App\Booking::BOOKING_TYPE_INSTALLMENT ? $file->installment_price : $file->cash_price;

        if($sale_price == 0)
        {
            throw new \Exception("Sale price is 0", 1);
        }

        //SALES ENTRY REVERSE
        // FILE CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            null,
            $booking->property_file_id,
            'Sales Return booked',
            -$sale_price,
        );

        // SALES DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_PROPERTY_SALES_INCOME)->id,
            null,
            null,
            'Sales Return booked',
            $sale_price,
        );

        // COST OF SALES ENTRY REVERSE
        // COST ACCOUNT CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_PROPERTY_SALES_COST)->id,
            null,
            null,
            'Cost of Sales booked',
            -$file->cost,
        );

        // LAND COST DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_LAND_COST)->id,
            null,
            null,
            'Cost of Sales booked',
            $file->cost,
        );

        // DOWNPAYMENT RETURNED ENTRY
        // FILE CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            null,
            $model->property_file_id,
            'Downpayment received',
            -$model->down_payment_received,
        );

        // DOWNPAYMENT RECEIVED ACCOUNT DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->down_payment_received_account_id,
            null,
            null,
            'Downpayment received',
            $model->down_payment_received,
        );

        // FORM PROCESSING FEE ENTRY
        // FORM PROCESSING FEE RECEIVED ACCOUNT DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->form_processing_fee_received_account_id,
            null,
            null,
            'Form Processing Fee',
            $model->form_processing_fee_received,
        );

        // FORM PROCESSING FEE INCOME CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_FORM_PROCESSING_FEE_INCOME)->id,
            null,
            null,
            'Form Processing Fee',
            -$model->form_processing_fee_received,
        );

        // DEALER COMMISSION ENTRY
        // COMMISSION EXPENSE DEBIT
        $commission_amount = $model->dealer_commission_amount != null ? $model->dealer_commission_amount : 0;
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_DEALER_COMMISSION_EXPENSE)->id,
            null,
            null,
            'Dealer Commission',
            $commission_amount,
        );

        // DEALER ACCOUNT CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            $model->dealer_id,
            null,
            'Dealer Commission',
            -$commission_amount,
        );
    }
}
