<?php

namespace App\Admin\Controllers;

use App\Property;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\SelectHelper;

class PropertyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Properties';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Property);

        $grid->column('id', __('Id'));
        $grid->column('project_id', __('Project id'));
        $grid->column('phase_id', __('Phase id'));
        $grid->column('block_id', __('Block id'));
        $grid->column('property_type_id', __('Property type id'));
        $grid->column('name', __('Name'));
        $grid->column('marlas', __('Marlas'));
        $grid->column('is_corner', __('Is corner'));
        $grid->column('is_facing_park', __('Is facing park'));
        $grid->column('is_on_boulevard', __('Is on boulevard'));
        $grid->column('cash_price', __('Cash price'));
        $grid->column('installment_price', __('Installment price'));
        $grid->column('property_status', __('Property Status'));
        
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
        $show = new Show(Property::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('project_id', __('Project id'));
        $show->field('phase_id', __('Phase id'));
        $show->field('block_id', __('Block id'));
        $show->field('property_type_id', __('Property type id'));
        $show->field('name', __('Name'));
        $show->field('marlas', __('Marlas'));
        $show->field('is_corner', __('Is corner'));
        $show->field('is_facing_park', __('Is facing park'));
        $show->field('is_on_boulevard', __('Is on boulevard'));
        $show->field('cash_price', __('Cash price'));
        $show->field('installment_price', __('Installment price'));
        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Property);

        $form->select('project_id', 'Project')
        ->addVariables(['show_add_button' => true])
        ->options(function ($id) {
            $project = \App\Project::find($id);
        
            if ($project) {
                return [$project->id => $project->name . '['.$project->short_name.']'];
            }
        })
        ->ajax(url('select-data?model=' . urlencode('\App\Project') . '|name,short_name|name:Name,short_name:Short Name'));

        $form->number('phase_id', __('Phase id'));
        $form->number('block_id', __('Block id'));
        $form->number('property_type_id', __('Property type id'));
        $form->text('name', __('Name'));
        $form->decimal('marlas', __('Marlas'));
        $form->switch('is_corner', __('Is corner'));
        $form->switch('is_facing_park', __('Is facing park'));
        $form->switch('is_on_boulevard', __('Is on boulevard'));
        $form->decimal('cash_price', __('Cash price'));
        $form->decimal('installment_price', __('Installment price'));
        
        return $form;
    }
}
