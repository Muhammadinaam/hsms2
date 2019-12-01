<?php

namespace App\Admin\Controllers;

use App\Transfer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TransferController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Transfers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transfer);

        $grid->column('id', __('Id'));
        $grid->column('transfer_details', __('Transfer details'));
        $grid->column('property.text_for_select', __('Property id'));
        $grid->column('transferredFrom.text_for_select', __('Transferred from id'));
        $grid->column('transferredTo.text_for_select', __('Transferred to id'));

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
        $show = new Show(Transfer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('transfer_details', __('Transfer details'));
        $show->field('property_id', __('Property id'));
        $show->field('transferred_from_id', __('Transferred from id'));
        $show->field('transferred_to_id', __('Transferred to id'));
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
        $form = new Form(new Transfer);
        $id = isset(request()->route()->parameters()['transfer']) ? 
            request()->route()->parameters()['transfer'] : null;
        $transfer = \App\Transfer::find($id);

        $form->textarea('transfer_details', __('Transfer details'));
        
        $form->select('property_id', __('Property'))
        ->rules('required')
        ->addVariables(['add_button_url' => 'admin/properties/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Property', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Property'), 'id', 'text_for_select');
        
        $form->display('transferredFrom.text_for_select', __('Transferred from (Auto)'));
        $form->select('transferred_to_id', __('Transferred to'))
        ->addVariables(['add_button_url' => 'admin/people/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Person', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Person'), 'id', 'text_for_select');

        return $form;
    }
}
