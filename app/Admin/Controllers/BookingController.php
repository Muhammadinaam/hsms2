<?php

namespace App\Admin\Controllers;

use App\Booking;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\UpdateHelpers;
use Illuminate\Support\MessageBag;
use App\Helpers\BookingStatusConstants;
use App\Helpers\GeneralHelpers;

class BookingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bookings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Booking);

        $grid->column('date', __('Date'))->date('d-M-Y');
        $grid->column('customer.text_for_select', __('Customer'));
        $grid->column('propertyFile.text_for_select', __('Property File'));
        $grid->column('customer_amount_received', __('Amount received'));
        $grid->column('dealer.text_for_select', __('Dealer id'));
        $grid->column('dealer_commission_amount', __('Dealer commission amount'));
        $grid->column('status', __('Booking Status'))->display(function($status){
            return \App\Helpers\StatusesHelper::statusTitle($status);
        })->filter([
            \App\Helpers\StatusesHelper::BOOKED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::BOOKED),
            \App\Helpers\StatusesHelper::ALLOTTED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::ALLOTTED),
        ]);

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
        $show = new Show(Booking::findOrFail($id));

        $show->field('date', __('Date'));
        $show->field('customer_id', __('Customer id'));
        $show->field('customer_amount_received', __('Amount received'));
        $show->field('dealer_id', __('Dealer id'));
        $show->field('dealer_commission_amount', __('Dealer commission amount'));
        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Booking);
        $id = isset(request()->route()->parameters()['booking']) ? 
            request()->route()->parameters()['booking'] : null;
        $booking = \App\Booking::find($id);

        $form->saving(function (Form $form) use ($id, $booking) {
            
            if($booking != null && !$booking->isEditableOrCancellable())
            {
                return \App\Helpers\GeneralHelpers::ReturnJsonErrorResponse('Cannot Update', 'Status of Booking is [' . \App\Helpers\StatusesHelper::statusTitle($booking->status) . ']. It cannot be changed now.');
            }
        });

        $form->saved(function(Form $form) {

            $property_file = $form->model()->propertyFile;
            $property_file->dealer_id = null;
            $property_file->holder_id = $form->model()->customer_id;
            $property_file->save();

            self::postToLedger($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d H:i:s'))
        ->rules('required');

        $property_file_where = 'status = \''. \App\Helpers\StatusesHelper::AVAILABLE . '\' ';
        if($booking != null)
        {
            $property_file_where .= ' OR id = \'' . $booking->property_file_id . '\'';
        }

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'property_file_id', 
            __('Property File'), 
            '', 
            '\App\PropertyFile',
            $property_file_where)
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'customer_id', 
            __('Customer'), 
            'admin/people/create', 
            '\App\Person')
            ->rules('required');

        $form->decimal('customer_amount_received', __('Amount received'))
        ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'customer_amount_received_account_id', 
            __('Customer Amount Received Account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Account Head in which amount received will be debited')
            ->rules('required');
        
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'dealer_id', 
            __('Dealer'), 
            'admin/people/create', 
            '\App\Person')
            ->rules('required');

        $form->decimal('dealer_commission_amount', __('Dealer commission amount'))
        ->rules('required');
        
        return $form;
    }

    public static function postToLedger(\App\Booking $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Booking not saved correctly", 1);   
        }

        $project_id = $model->propertyFile->project_id;
        $phase_id = $model->propertyFile->phase_id;
        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $model->date, 
            \App\Ledger::CUSTOMER_BOOKING, 
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        //SALES ENTRY

        // CUSTOMER AMOUNT RECEIVED
        // CASH / BANK DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->customer_amount_received,
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
