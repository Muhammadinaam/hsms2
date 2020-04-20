<?php

namespace App\Admin\Controllers;

use App\PaymentPlan;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentPlanController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment Plans';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentPlan);

        $grid->column('id', __('Id'));
        $grid->column('propertyFile.text_for_select', __('Property File'));

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
        $show = new Show(PaymentPlan::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('property_file_id', __('Property file id'));
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
        $form = new Form(new PaymentPlan);
        $id = isset(request()->route()->parameters()['payment_plan']) ? 
            request()->route()->parameters()['payment_plan'] : null;
        $payment_plan = \App\PaymentPlan::find($id);

        $form->saved(function(Form $form) {

            $model = $form->model();
            \App\PaymentPlanSchedule::where('payment_plan_id', $model->id)->delete();

            foreach($model->paymentPlanDetails as $paymentPlanDetail)
            {
                for($i = 0; $i < $paymentPlanDetail->number_of_payments; $i++)
                {
                    $payment_plan_schedule = new \App\PaymentPlanSchedule;
                    $payment_plan_schedule->payment_plan_id = $model->id;
                    $payment_plan_schedule->property_file_id = $model->property_file_id;
                    $payment_plan_schedule->date = 
                        \Carbon\Carbon::parse($paymentPlanDetail->starting_date)
                        ->addDays($i * $paymentPlanDetail->days_between_each_payment);
                    $payment_plan_schedule->amount = $paymentPlanDetail->amount;
                    $payment_plan_schedule->save();
                }
            }

        });

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'property_file_id', 
            __('Property File'), 
            '', 
            '\App\PropertyFile')
            ->rules('required');

        $form->hasMany('paymentPlanDetails', __('Payment Plan Details'), function (Form\NestedForm $form) {
            $form->decimal('amount', __('Amount'))->rules('required');
            $form->number('number_of_payments', __('Number of Payments'))->rules('required|numeric|gt:0');
            $form->number('days_between_each_payment', __('Days between each payment'))->rules('required');
            $form->date('starting_date', __('Starting Date'))->rules('required');
        })->mode('table');

        return $form;
    }
}
