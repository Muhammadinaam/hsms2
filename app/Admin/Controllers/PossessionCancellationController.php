<?php

namespace App\Admin\Controllers;

use App\PossessionCancellation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PossessionCancellationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Possession Cancellations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PossessionCancellation);

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date of cancellation'));
        $grid->column('cancellation_reason', __('Cancellation reason'));
        $grid->column('possession_id', __('Possession id'));
        

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
        $show = new Show(PossessionCancellation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date of cancellation'));
        $show->field('cancellation_reason', __('Cancellation reason'));
        $show->field('possession_id', __('Possession id'));
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
        $form = new Form(new PossessionCancellation);
        $id = isset(request()->route()->parameters()['possession_cancellation']) ? 
            request()->route()->parameters()['possession_cancellation'] : null;
        $possession_cancellation = \App\PossessionCancellation::find($id);

        $form->saving(function (Form $form) use ($id, $possession_cancellation) {
            
            if($possession_cancellation != null && $possession_cancellation->possession != null)
            {
                $old_possession = $possession_cancellation->possession;
                $ret = $old_possession->unsetCancelledStatus();
                if ($ret instanceof Response) {
                    return $ret;
                }
            }

            $new_possession = \App\Possession::find($form->possession_id);
            $ret = $new_possession->setCancelledStatus();
            if ($ret instanceof Response) {
                return $ret;
            }

        });

        $form->date('date', __('Date of cancellation'))->default(date('Y-m-d'));
        $form->text('cancellation_reason', __('Cancellation reason'));
        
        $possession_where = 'status = \''. \App\Helpers\StatusesHelper::POSSESSED .'\'';
        $possession_where .=  $id != null ? ' OR possessions.id = ' . $possession_cancellation->possession_id : '';
        $form->select('possession_id', __('Possession'))
        ->addVariables(['add_button_url' => ''])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Possession', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Possession', $possession_where), 'id', 'text_for_select');

        return $form;
    }
}
