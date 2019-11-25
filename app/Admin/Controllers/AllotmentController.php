<?php

namespace App\Admin\Controllers;

use App\Allotment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\BookingStatusConstants;
use Illuminate\Support\MessageBag;
use App\Helpers\PropertyStatusConstants;

class AllotmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Allotments';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Allotment);

        $grid->column('date_of_allotment', __('Date of allotment'));
        $grid->column('booking_id', __('Booking id'));
        $grid->column('property_id', __('Property id'));
        $grid->column('any_amount_received_before_or_at_allotment_time', __('Any amount received before or at allotment time'));
        $grid->column('amount_received_date', __('Amount received date'));
        $grid->column('amount_received_account_id', __('Amount received account id'));
        $grid->column('agent_id', __('Agent id'));
        $grid->column('agent_commission_amount', __('Agent commission amount'));

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
        $show = new Show(Allotment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_of_allotment', __('Date of allotment'));
        $show->field('booking_id', __('Booking id'));
        $show->field('property_id', __('Property id'));
        $show->field('any_amount_received_before_or_at_allotment_time', __('Any amount received before or at allotment time'));
        $show->field('amount_received_date', __('Amount received date'));
        $show->field('amount_received_account_id', __('Amount received account id'));
        $show->field('agent_id', __('Agent id'));
        $show->field('agent_commission_amount', __('Agent commission amount'));
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
        $form = new Form(new Allotment);

        $id = isset(request()->route()->parameters()['booking_cancellation']) ? 
            request()->route()->parameters()['booking_cancellation'] : null;
        $allotment = \App\Allotment::find($id);

        $form->saving(function (Form $form) use ($id, $allotment) {
            
            $booking_id = $form->booking_id;
            $booking = \App\Booking::find($booking_id);

            if($booking->booking_status != BookingStatusConstants::$booked 
                && $booking->booking_status != BookingStatusConstants::$allotted )
            {
                $error = new MessageBag([
                    'title'   => 'Error',
                    'message' => 'Status of this booking is ' . $booking->booking_status . '. This cannot be changed now',
                ]);
            
                return back()->with(compact('error'));    
            } 

            if($allotment != null)
            {
                $previous_allotted_booking_id = $allotment->booking_id;
                if($id != null && $previous_allotted_booking_id != $booking->id) 
                {
                    //revert previous booking if editing cancellation form
                    $previous_allotted_booking = \App\Booking::find($previous_allotted_booking_id);
                    $previous_allotted_booking->booking_status = BookingStatusConstants::$booked;
                    $previous_allotted_booking->save();
                }
            }

            $booking->booking_status = BookingStatusConstants::$allotted;
            $booking->save();



            $property_id = $form->property_id;
            $property = \App\Property::find($property_id);

            if($property->property_status != PropertyStatusConstants::$available
                && $property->property_status != PropertyStatusConstants::$allotted )
            {
                $error = new MessageBag([
                    'title'   => 'Error',
                    'message' => 'Status of this property is ' . $property->property_status . '. This cannot be changed now',
                ]);
            
                return back()->with(compact('error'));    
            } 

            if($allotment != null)
            {
                $previous_allotted_property_id = $allotment->property_id;
                if($id != null && $previous_allotted_property_id != $property->id) 
                {
                    //revert previous property if editing cancellation form
                    $previous_allotted_property = \App\Booking::find($previous_allotted_property_id);
                    $previous_allotted_property->property_status = PropertyStatusConstants::$available;
                    $previous_allotted_property->save();
                }
            }

            $property->property_status = PropertyStatusConstants::$allotted;
            $property->save();

        });


        $booking_where = 'booking_status = \''. BookingStatusConstants::$booked .'\'';
        $booking_where .=  $id != null ? ' OR bookings.id = ' . $allotment->booking_id : '';

        $property_where = 'property_status = \''. PropertyStatusConstants::$available .'\'';
        $property_where .=  $id != null ? ' OR properties.id = ' . $allotment->property_id : '';



        $form->date('date_of_allotment', __('Date of allotment'))->default(date('Y-m-d'));
        
        $form->select('booking_id', __('Booking'))
        ->rules('required')
        ->addVariables(['add_button_url' => 'admin/bookings/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Booking', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Booking', $booking_where), 'id', 'text_for_select');
        
        $form->select('property_id', __('Property'))
        ->rules('required')
        ->addVariables(['add_button_url' => 'admin/properties/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Property', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Property', $property_where), 'id', 'text_for_select');

        $form->decimal('any_amount_received_before_or_at_allotment_time', __('Any amount received before or at allotment time'));
        $form->date('amount_received_date', __('Amount received date'))->default(date('Y-m-d'));
        $form->number('amount_received_account_id', __('Amount received account id'));
        
        $form->select('agent_id', __('Agent id'))
        ->addVariables(['add_button_url' => 'admin/people/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Person', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Person'), 'id', 'text_for_select');
        
        $form->decimal('agent_commission_amount', __('Agent commission amount'));

        $form->hasMany('paymentPlans', function (Form\NestedForm $form) {
            $form->date('starting_date')->default(date('Y-m-d'));
            $form->decimal('amount', "Amount");
            $form->number('number_of_payments', 'Number of Payments');
            $form->number('days_between_payments', 'Days between payments');
        })->mode('table');

        return $form;
    }
}
