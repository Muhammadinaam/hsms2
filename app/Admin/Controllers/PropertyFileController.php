<?php

namespace App\Admin\Controllers;

use App\PropertyFile;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PropertyFileController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Property Files';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PropertyFile);

        $grid->column('project.name', __('Project'));
        $grid->column('phase.name', __('Phase'));
        
        $grid->column('file_number', __('File number'));
        $grid->column('marlas', __('Marlas'));

        $grid->column('propertyType.name', __('Property Type'));
        $grid->column('is_corner', __('Corner'))->bool();
        $grid->column('is_facing_park', __('Facing Park'))->bool();
        $grid->column('is_on_boulevard', __('On Boulevard'))->bool();
        $grid->column('cash_price', __('Cash price'));
        $grid->column('installment_price', __('Installment price'));
        
        $grid->column('dealer.text_for_select', __('Dealer'));
        $grid->column('holder.text_for_select', __('Holder'));
        
        $grid->column('status', __('Status'))->display(function($status){
            return \App\Helpers\StatusesHelper::statusTitle($status);
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
        $show = new Show(PropertyFile::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('project_id', __('Project id'));
        $show->field('phase_id', __('Phase id'));
        $show->field('file_number', __('File number'));
        $show->field('marlas', __('Marlas'));
        $show->field('property_id', __('Property id'));
        $show->field('dealer_id', __('Dealer id'));
        $show->field('holder_id', __('Holder id'));
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
        $form = new Form(new PropertyFile);

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'project_id', 
            __('Project'), 
            'admin/projects/create', 
            '\App\Project')
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'phase_id', 
            __('Phase'), 
            'admin/phases/create', 
            '\App\Phase')
            ->rules('required');

        $form->text('file_number', __('File number'))
            ->creationRules(['required', "unique:property_files"])
            ->updateRules(['required', "unique:property_files,file_number,{{id}}"]);

        $form->decimal('marlas', __('Marlas'))
        ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'property_type_id', 
            __('Property type'), 
            'admin/property-types/create', 
            '\App\PropertyType')
            ->rules('required');

        $form->switch('is_corner', __('Is corner'));
        $form->switch('is_facing_park', __('Is facing park'));
        $form->switch('is_on_boulevard', __('Is on boulevard'));

        $form->divider('Price and Cost Information');

        $form->decimal('cash_price', __('Cash price'))->rules('required');
        $form->decimal('installment_price', __('Installment price'))->rules('required');
        $form->decimal('cost', __('Cost'))->rules('required');

        return $form;
    }
}
