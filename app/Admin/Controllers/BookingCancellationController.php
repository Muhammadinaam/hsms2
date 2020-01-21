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

        $grid->column('form_processing_fee_returned', __('Form Processing Fee Returned'));
        $grid->column('down_payment_returned', __('Down Payment Returned'));
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
                $old_property_file->save();
                if ($ret instanceof Response) {
                    return $ret;
                }
            }

            $new_booking = \App\Booking::find($form->booking_id);
            $new_property_file = $new_booking->propertyFile;
            $new_property_file->dealer_id = $new_property_file->sold_by_dealer_id;
            $new_property_file->sold_by_dealer_id = null;
            $new_property_file->save();
            $ret = $new_booking->setCancelledStatus();
            if ($ret instanceof Response) {
                return $ret;
            }

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

        $form->divider('Down Payment Returned');

        $form->decimal('down_payment_returned', __('Down payment returned'))
        ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'down_payment_returned_account_id', 
            __('Down payment returnded account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Cash or Bank Account from which amount is being returned')
            ->rules('required');
        
        $form->divider('Form / Processing fee returned');
        
        $form->decimal('form_processing_fee_returned', __('Form / Processing fee returned'))
            ->rules('required');
    
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'form_processing_fee_returned_account_id', 
            __('Form / Processing fee returned account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Cash or Bank Account from which amount is being returned')
            ->rules('required');

        $form->decimal('dealer_commission_to_be_returned', __('Dealer commission to be returned'));

        return $form;
    }
}
