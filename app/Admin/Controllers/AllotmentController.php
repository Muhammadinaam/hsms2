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
        $grid->column('booking.text_for_select', __('Booking'));
        $grid->column('property_number', __('Property Number'));
        $grid->column('block.text_for_select', __('Block'));
        $grid->column('status', __('Allotment Status'));

        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();
        
            // Add a column filter
            $filter->where(function($query){
                // $query->booking->searchForSelect($this->input);
                $query->whereHas('booking', function($query){
                    $query->whereHas('propertyFile', function($query) {
                        $query->where('file_number', 'like', '%'.$this->input.'%');
                    });
                });
                
            }, 'Property File');
        
        });

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

            if($allotment != null)
            {
                $previous_booking = $allotment->booking;
                $previous_property_file = $previous_booking->propertyFile;     
                $previous_property_file->property_number = null;
                $previous_property_file->block_id = null;
                $previous_property_file->save();
            }

            $new_booking = \App\Booking::find($form->booking_id);
            $new_property_file = $new_booking->propertyFile;     
            $new_property_file->property_number = $form->property_number;
            $new_property_file->block_id = $form->block_id;
            $new_property_file->save();
        });

        $booking_where = 'status = \''. \App\Helpers\StatusesHelper::BOOKED .'\'';
        $booking_where .=  $id != null ? ' OR bookings.id = ' . $allotment->booking_id : '';


        $form->date('date', __('Date of allotment'))->default(date('Y-m-d'));

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'booking_id', 
            __('Booking'), 
            'admin/bookings/create', 
            '\App\Booking');

        $form->text('property_number', __('Property Number'));

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'block_id', 
            __('Block'), 
            'admin/blocks/create', 
            '\App\Block');

        return $form;
    }

}
