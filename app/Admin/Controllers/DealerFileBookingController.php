<?php

namespace App\Admin\Controllers;

use App\DealerFileBooking;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DealerFileBookingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dealer File Bookings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DealerFileBooking);

        $grid->column('date', __('Date'))->date('d-M-Y');
        $grid->column('dealer_id', __('Dealer id'));
        $grid->column('dealer_amount_received', __('Dealer amount received'));
        $grid->column('dealerAmountReceivedAccount.text_for_select', __('Dealer amount received account'));

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
        $show = new Show(DealerFileBooking::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('dealer_id', __('Dealer id'));
        $show->field('dealer_amount_received', __('Dealer amount received'));
        $show->field('dealer_amount_received_account_id', __('Dealer amount received account id'));
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
        $form = new Form(new DealerFileBooking);
        $id = isset(request()->route()->parameters()['dealer_file_booking']) ? 
            request()->route()->parameters()['dealer_file_booking'] : null;
        $dealer_file_booking = \App\DealerFileBooking::find($id);

        $form->saved(function (Form $form) {
            foreach(request()->dealerFileBookingDetails as $detail)
            {
                if($detail['_remove_'] != '1')
                {
                    $file_id = $detail['file_id'];

                    $file = \App\File::find($file_id);
                    $file->dealer_id = request()->dealer_id;
                    $file->save();
                }
            }

            \App\Ledger::postDealerFileBooking($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'dealer_id', 
            __('Dealer'), 
            'admin/people/create', 
            '\App\Person')
            ->rules('required');
        
        $form->decimal('dealer_amount_received', __('Dealer amount received'))
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'dealer_amount_received_account_id', 
            __('Dealer amount received account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Account Head in which amount received will be debited')
            ->rules('required');

        $form->hasMany('dealerFileBookingDetails', __('Files'), function (Form\NestedForm $form) use ($dealer_file_booking) {
            
            $file_where = ' dealer_id is null ';

            if($dealer_file_booking != null)
            {
                $details = $dealer_file_booking->dealerFileBookingDetails;
                if($details != null && count($details) > 0)
                {
                    $file_ids = $details->pluck('file_id')->toArray();
                    $file_where = '(' . $file_where . ' OR id in ('  . implode(',', $file_ids) . ') )';
                }
            }

            \App\Helpers\SelectHelper::buildAjaxSelect(
                $form, 
                'file_id', 
                __('File'), 
                '', 
                '\App\File',
                $file_where)
                ->rules('required');

        })->mode('table');

        return $form;
    }
}
