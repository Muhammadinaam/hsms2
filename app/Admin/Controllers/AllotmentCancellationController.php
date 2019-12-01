<?php

namespace App\Admin\Controllers;

use App\AllotmentCancellation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AllotmentCancellationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Allotment Cancellation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AllotmentCancellation);

        $grid->column('id', __('Id'));
        $grid->column('date_of_cancellation', __('Date of cancellation'))->date('d-M-Y');
        $grid->column('cancellation_reason', __('Cancellation Reason'));
        $grid->column('allotment_id', __('Allotment id'));
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
        $show = new Show(AllotmentCancellation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_of_cancellation', __('Date of cancellation'));
        $show->field('allotment_id', __('Allotment id'));
        $show->field('customer_amount_returned', __('Customer amount returned'));
        $show->field('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $show->field('agent_commission_to_be_returned', __('Agent commission to be returned'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AllotmentCancellation);
        $id = isset(request()->route()->parameters()['allotment_cancellation']) ? 
            request()->route()->parameters()['allotment_cancellation'] : null;
        $allotment_cancellation = \App\AllotmentCancellation::find($id);

        $form->saving(function (Form $form) use ($id, $allotment_cancellation) {
            
            if($allotment_cancellation != null && $allotment_cancellation->allotment != null)
            {
                $old_allotment = $allotment_cancellation->allotment;
                $ret = $old_allotment->unsetCancelledStatus();
                if ($ret instanceof Response) {
                    return $ret;
                }
            }

            $new_allotment = \App\Allotment::find($form->allotment_id);
            $ret = $new_allotment->setCancelledStatus();
            if ($ret instanceof Response) {
                return $ret;
            }

        });

        $form->date('date_of_cancellation', __('Date of cancellation'))->default(date('Y-m-d'));
        $form->text('cancellation_reason', __('Cancellation Reason'));
        
        $allotment_where = 'status = \''. \App\Helpers\StatusesHelper::ALLOTTED .'\'';
        $allotment_where .=  $id != null ? ' OR allotments.id = ' . $allotment_cancellation->allotment_id : '';
        $form->select('allotment_id', __('Allotment'))
        ->addVariables(['add_button_url' => ''])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Allotment', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Allotment', $allotment_where), 'id', 'text_for_select');
        
        $form->decimal('customer_amount_returned', __('Customer amount returned'));
        $form->number('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $form->decimal('agent_commission_to_be_returned', __('Agent commission to be returned'));

        return $form;
    }
}
