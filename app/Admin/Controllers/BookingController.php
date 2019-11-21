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
    protected $title = 'App\Booking';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Booking);

        $grid->column('id', __('Id'));
        $grid->column('date_of_booking', __('Date of booking'));
        $grid->column('customer_id', __('Customer id'));
        $grid->column('is_corner', __('Is corner'));
        $grid->column('is_facing_park', __('Is facing park'));
        $grid->column('is_on_boulevard', __('Is on boulevard'));
        $grid->column('amount_received', __('Amount received'));
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
        $show = new Show(Booking::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_of_booking', __('Date of booking'));
        $show->field('customer_id', __('Customer id'));
        $show->field('is_corner', __('Is corner'));
        $show->field('is_facing_park', __('Is facing park'));
        $show->field('is_on_boulevard', __('Is on boulevard'));
        $show->field('amount_received', __('Amount received'));
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

        $form->datetime('date_of_booking', __('Date of booking'))->default(date('Y-m-d H:i:s'));
        $form->number('customer_id', __('Customer id'));
        $form->switch('is_corner', __('Is corner'));
        $form->switch('is_facing_park', __('Is facing park'));
        $form->switch('is_on_boulevard', __('Is on boulevard'));
        $form->decimal('amount_received', __('Amount received'));
        $form->number('agent_id', __('Agent id'));
        $form->decimal('agent_commission_amount', __('Agent commission amount'));
        
        return $form;
    }
}
