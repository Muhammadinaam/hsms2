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

        $grid->column('date', __('Date'));
        $grid->column('dealer_id', __('Dealer id'));
        $grid->column('dealer_amount_received', __('Dealer amount received'));
        $grid->column('dealer_amount_received_account_id', __('Dealer amount received account id'));

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

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        
        $form->select('dealer_id', __('Dealer id'))
        ->addVariables(['add_button_url' => 'admin/people/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Person', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Person'), 'id', 'text_for_select');
        
        $form->decimal('dealer_amount_received', __('Dealer amount received'));
        $form->select('dealer_amount_received_account_id', __('Dealer amount received account'))
            ->addVariables(['add_button_url' => 'admin/account-heads/create'])
            ->options(function ($id) {
                return \App\Helpers\SelectHelper::selectedOptionData('\App\AccountHead', $id);
            })
            ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\AccountHead'), 'id', 'text_for_select')
            ->help('Account Head in which amount received will be debited');

        return $form;
    }
}
