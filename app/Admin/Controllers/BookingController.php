<?php

namespace App\Admin\Controllers;

use App\Booking;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        $grid->column('booking_number', __('Booking Number'));
        $grid->column('date_of_booking', __('Date of booking'))->date('d-M-Y');
        $grid->column('project.name', __('Project'));
        $grid->column('phase.name', __('Phase'));
        $grid->column('customer.text_for_select', __('Customer'));
        $grid->column('booking_for_marlas', __('Booking For Marlas'));
        $grid->column('is_corner', __('Is corner'))->bool();
        $grid->column('is_facing_park', __('Is facing park'))->bool();
        $grid->column('is_on_boulevard', __('Is on boulevard'))->bool();
        $grid->column('customer_amount_received', __('Amount received'));
        $grid->column('agent.text_for_select', __('Agent id'));
        $grid->column('agent_commission_amount', __('Agent commission amount'));
        $grid->column('booking_status', __('Booking Status'));
        
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

        $show->field('date_of_booking', __('Date of booking'));
        $show->field('customer_id', __('Customer id'));
        $show->field('is_corner', __('Is corner'));
        $show->field('is_facing_park', __('Is facing park'));
        $show->field('is_on_boulevard', __('Is on boulevard'));
        $show->field('customer_amount_received', __('Amount received'));
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
        $form = new Form(new Booking);

        $form->saving(function (Form $form) {
            
            $id = $form->id;
            
            if($id == null)
            {
                // get previous id
                $previous_booking = \App\Booking::orderBy('booking_sequence_number', 'desc')->first();
                $previous_number = $previous_booking == null ? 0 :$previous_booking->booking_sequence_number; 

                $form->model()->booking_sequence_number = $previous_number + 1;
            }
        });

        $form->date('date_of_booking', __('Date of booking'))->default(date('Y-m-d H:i:s'));
        $form->display('booking_number', __('Booking Number (Auto)'));
        $form->select('customer_id', __('Customer'))
        ->addVariables(['add_button_url' => 'admin/people/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Person', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Person'), 'id', 'text_for_select');

        $form->select('project_id', 'Project')
        ->addVariables(['add_button_url' => 'admin/projects/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Project', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Project'), 'id', 'text_for_select');

        $form->select('phase_id', __('Phase'))
        ->addVariables(['add_button_url' => 'admin/phases/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Phase', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Phase'), 'id', 'text_for_select');

        $form->select('property_type_id', __('Property Type'))
        ->addVariables(['add_button_url' => 'admin/property-types/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\PropertyType', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\PropertyType'), 'id', 'text_for_select');

        $form->decimal('booking_for_marlas', __('Booking For Marlas'));
        $form->switch('is_corner', __('Is corner'));
        $form->switch('is_facing_park', __('Is facing park'));
        $form->switch('is_on_boulevard', __('Is on boulevard'));
        $form->decimal('customer_amount_received', __('Amount received'));
        $form->number('customer_amount_received_account_id', __('Customer Amount Received Account ID'));
        
        $form->select('agent_id', __('Agent id'))
        ->addVariables(['add_button_url' => 'admin/people/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Person', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Person'), 'id', 'text_for_select');

        $form->decimal('agent_commission_amount', __('Agent commission amount'));
        
        return $form;
    }
}
