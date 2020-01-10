<?php

namespace App\Admin\Controllers;

use App\Allotment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

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

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date of allotment'));
        $grid->column('booking_id', __('Booking id'));
        $grid->column('property_id', __('Property id'));
        $grid->column('any_amount_received_before_or_at_allotment_time', __('Any amount received before or at allotment time'));
        $grid->column('amount_received_date', __('Amount received date'));
        $grid->column('amount_received_account_id', __('Amount received account id'));
        $grid->column('dealer_id', __('Dealer id'));
        $grid->column('dealer_commission_amount', __('Dealer commission amount'));
        $grid->column('status', __('Allotment Status'));

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
        $show->field('date', __('Date of allotment'));
        $show->field('booking_id', __('Booking id'));
        $show->field('property_id', __('Property id'));
        $show->field('any_amount_received_before_or_at_allotment_time', __('Any amount received before or at allotment time'));
        $show->field('amount_received_date', __('Amount received date'));
        $show->field('amount_received_account_id', __('Amount received account id'));
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
        $form = new Form(new Allotment);

        $id = isset(request()->route()->parameters()['allotment']) ? 
            request()->route()->parameters()['allotment'] : null;
        $allotment = \App\Allotment::find($id);

        $form->saving(function (Form $form) use ($id, $allotment) {

            if($allotment != null && !$allotment->isEditableOrCancellable())
            {
                return \App\Helpers\GeneralHelpers::ReturnJsonErrorResponse('Cannot Update', 'Status of Allotment is [' . \App\Helpers\StatusesHelper::statusTitle($allotment->status) . ']. It cannot be changed now.');
            }

            $new_booking = \App\Booking::find($form->booking_id);
            $new_property = \App\Property::find($form->property_id);
            $booking_and_property_match = $this->bookingAndPropertyMatch($new_booking, $new_property);
            if( $booking_and_property_match !== true )
            {
                $error = $booking_and_property_match;
            
                return back()->with(compact('error')); 
            }
        });


        $booking_where = 'status = \''. \App\Helpers\StatusesHelper::BOOKED .'\'';
        $booking_where .=  $id != null ? ' OR bookings.id = ' . $allotment->booking_id : '';

        $property_where = 'status = \''. \App\Helpers\StatusesHelper::AVAILABLE .'\'';
        $property_where .=  $id != null ? ' OR properties.id = ' . $allotment->property_id : '';



        $form->date('date', __('Date of allotment'))->default(date('Y-m-d'));
        
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
        
        $form->select('dealer_id', __('Dealer id'))
        ->addVariables(['add_button_url' => 'admin/people/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Person', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Person'), 'id', 'text_for_select');
        
        $form->decimal('dealer_commission_amount', __('Dealer commission amount'));

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
