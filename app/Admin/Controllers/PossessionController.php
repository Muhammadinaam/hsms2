<?php

namespace App\Admin\Controllers;

use App\Possession;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PossessionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Possessions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Possession);

        $grid->column('id', __('Id'));
        $grid->column('date_of_possession', __('Date of possession'));
        $grid->column('allotment_id', __('Allotment id'));
        $grid->column('status', __('Status'));

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
        $show = new Show(Possession::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_of_possession', __('Date of possession'));
        $show->field('allotment_id', __('Allotment id'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Possession);
        $id = isset(request()->route()->parameters()['possession']) ? 
            request()->route()->parameters()['possession'] : null;
        $possession = \App\Possession::find($id);

        $form->saving(function (Form $form) use ($id, $possession) {

            if($possession != null && !$possession->isEditableOrCancellable())
            {
                return \App\Helpers\GeneralHelpers::ReturnJsonErrorResponse('Cannot Update', 'Status of Possession is [' . \App\Helpers\StatusesHelper::statusTitle($possession->status) . ']. It cannot be changed now.');
            }

        });

        $form->date('date_of_possession', __('Date of possession'))->default(date('Y-m-d'));
        
        $allotment_where = 'status = \''. \App\Helpers\StatusesHelper::ALLOTTED .'\'';
        $allotment_where .=  $id != null ? ' OR allotments.id = ' . $allotment_cancellation->allotment_id : '';
        $form->select('allotment_id', __('Allotment'))
        ->addVariables(['add_button_url' => ''])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Allotment', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Allotment', $allotment_where), 'id', 'text_for_select');

        return $form;
    }
}
