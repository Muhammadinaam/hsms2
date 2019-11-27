<?php

namespace App\Admin\Controllers;

use App\Allotment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\BookingStatusConstants;
use App\Helpers\UpdateStatus;
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
            
            $booking = \App\Booking::find($form->booking_id);
            $ret = UpdateStatus::UpdateStatusLogic(
                'Booking',
                $booking, 
                \App\Booking::class,
                'booking_status',
                $booking->booking_status != BookingStatusConstants::$booked 
                && $booking->booking_status != BookingStatusConstants::$allotted,
                $allotment,
                'booking_id',
                BookingStatusConstants::$booked,
                BookingStatusConstants::$allotted);

            if($ret !== true) {
                return $ret;
            }

            $property = \App\Property::find($form->property_id);
            $ret = UpdateStatus::UpdateStatusLogic(
                'Property',
                $property, 
                \App\Property::class,
                'property_status',
                $property->property_status != PropertyStatusConstants::$available 
                && $property->property_status != PropertyStatusConstants::$allotted,
                $allotment,
                'property_id',
                PropertyStatusConstants::$available,
                PropertyStatusConstants::$allotted);

            if($ret !== true) {
                return $ret;
            }

            $booking_and_property_match = $this->bookingAndPropertyMatch($booking, $property);
            if( $booking_and_property_match !== true )
            {
                $error = $booking_and_property_match;
            
                return back()->with(compact('error')); 
            }
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

    private function bookingAndPropertyMatch($booking, $property)
    {
        $attributes = [
            [ 'title' => 'Project', 'booking_db_field' => 'project_id', 'property_db_field' => 'project_id' ],
            [ 'title' => 'Phase', 'booking_db_field' => 'phase_id', 'property_db_field' => 'phase_id' ],
            [ 'title' => 'Marlas', 'booking_db_field' => 'booking_for_marlas', 'property_db_field' => 'marlas' ],
            [ 'title' => 'Is Corner', 'booking_db_field' => 'is_corner', 'property_db_field' => 'is_corner' ],
            [ 'title' => 'Is Facing Park', 'booking_db_field' => 'is_facing_park', 'property_db_field' => 'is_facing_park' ],
            [ 'title' => 'Is On Boulevard', 'booking_db_field' => 'is_on_boulevard', 'property_db_field' => 'is_on_boulevard' ],
        ];

        $error_message = '';

        $ret = true;
        foreach($attributes as $attribute)
        {
            if($booking->{$attribute['booking_db_field']} != $property->{$attribute['property_db_field']})
            {
                $error_message .= 'Value of [' . $attribute['title'] . '] in Booking is ' . $booking->{$attribute['booking_db_field']}
                    . ' while value of [' . $attribute['title'] . '] in Property is ' . $property->{$attribute['property_db_field']} . '. ';
                
                $ret = new MessageBag([
                    'title'   => 'Error',
                    'message' => $error_message,
                ]);
            }
        }

        return $ret;
    }
}
