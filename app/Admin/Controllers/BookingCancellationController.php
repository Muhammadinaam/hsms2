<?php

namespace App\Admin\Controllers;

use App\BookingCancellation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\BookingStatusConstants;
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
        $grid->column('date_of_cancellation', __('Date of cancellation'));
        $grid->column('cancellation_reason', __('Cancellation Reason'));
        $grid->column('booking.booking_number', __('Booking Number'));
        $grid->column('customer_amount_returned', __('Customer amount returned'));
        $grid->column('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $grid->column('agent_commission_to_be_returned', __('Agent commission to be returned'));

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
        $show->field('date_of_cancellation', __('Date of cancellation'));
        $show->field('booking_id', __('Booking id'));
        $show->field('customer_amount_returned', __('Customer amount returned'));
        $show->field('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $show->field('agent_commission_to_be_returned', __('Agent commission to be returned'));
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
            
            $old_booking = $booking_cancellation->booking;
            $ret = $old_booking->setBooked();
            if ($ret instanceof Response) {
                return $ret;
            }

            $new_booking = \App\Booking::find($form->booking_id);
            $ret = $new_booking->setCancelled();
            if ($ret instanceof Response) {
                return $ret;
            }

        });


        $booking_where = 'booking_status = \''. BookingStatusConstants::$booked .'\'';
        $booking_where .=  $id != null ? ' OR bookings.id = ' . $booking_cancellation->booking_id : '';

        $form->date('date_of_cancellation', __('Date of cancellation'))->default(date('Y-m-d H:i:s'));
        $form->text('cancellation_reason', __('Cancellation Reason'));
        $form->select('booking_id', __('Booking'))
        ->addVariables(['add_button_url' => ''])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Booking', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Booking', $booking_where), 'id', 'text_for_select');

        $form->decimal('customer_amount_returned', __('Customer amount returned'));
        $form->number('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $form->decimal('agent_commission_to_be_returned', __('Agent commission to be returned'));

        return $form;
    }
}
