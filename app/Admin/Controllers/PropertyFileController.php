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
        $grid->column('block.name', __('Block'));
        $grid->column('property_number', __('Property number'));
        $grid->column('marlas', __('Marlas'));

        $grid->column('propertyType.name', __('Property Type'));
        $grid->column('is_farmhouse', __('Farmhouse'))->bool();
        $grid->column('is_corner', __('Corner'))->bool();
        $grid->column('is_facing_park', __('Facing Park'))->bool();
        $grid->column('is_on_boulevard', __('On Boulevard'))->bool();
        $grid->column('cash_price', __('Cash price'));
        $grid->column('installment_price', __('Installment price'));
        
        $grid->column('soldByDealer.text_for_select', __('Sold By Dealer'));
        $grid->column('dealer.text_for_select', __('Dealer'));
        $grid->column('holder.text_for_select', __('Holder'));
        
        $grid->column('id', __('Status'))
        ->display(function($id){
            $status = \App\PropertyFile::find($id)->getOpenOrOtherStatus();
            return \App\Helpers\StatusesHelper::statusTitle($status);
        });

        \App\Helpers\GeneralHelpers::setGridRowActions($grid, true, true, true, true);

        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();
        
            // Add a column filter
            $filter->like('file_number', 'File Number');

            $filter->where(function ($query) {

                foreach ($this->input as $statusFilterValue) {
                    if($statusFilterValue == 'open')
                    {
                        $query->orWhere(function($query){
                            $query->open();
                        });
                    }
                    else if($statusFilterValue == \App\Helpers\StatusesHelper::AVAILABLE)
                    {
                        $query->orWhere(function($query){
                            $query->available();
                        });
                    }
                    else
                    {
                        $query->orWhere('status', $this->input);
                    }
                }

            }, 'Status', 'status')->multipleSelect([
                \App\Helpers\StatusesHelper::AVAILABLE => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::AVAILABLE),
                'open' => 'Open',
                \App\Helpers\StatusesHelper::BOOKED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::BOOKED),
                \App\Helpers\StatusesHelper::ALLOTTED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::ALLOTTED),
                \App\Helpers\StatusesHelper::POSSESSED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::POSSESSED),
            ]);
        
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

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'block_id', 
            __('Block'), 
            'admin/blocks/create', 
            '\App\Block')
            ->rules('required');

        $form->text('property_number', __('Property number'))
            ->creationRules(['required', "unique:property_files"])
            ->updateRules(['required', "unique:property_files,property_number,{{id}}"]);

        $form->text('file_number', __('File number'))
            ->creationRules(['required', "unique:property_files"])
            ->updateRules(['required', "unique:property_files,file_number,{{id}}"]);

        $marlas_options = [];
        foreach (\App\PropertyMarla::all() as $propertyMarla) {
            $marlas_options[$propertyMarla->marlas] = $propertyMarla->marlas;
        }
        $form->select('marlas', __('Marlas'))
            ->options($marlas_options)
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'property_type_id', 
            __('Property type'), 
            'admin/property-types/create', 
            '\App\PropertyType');

        $yes_no_states = [
            'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'No', 'color' => 'secondary'],
        ];
            
        $form->switch('is_farmhouse', __('Farmhouse'))->states($yes_no_states);

        $form->divider('Preference Location');
        $form->switch('is_corner', __('Corner'))->states($yes_no_states);
        $form->switch('is_facing_park', __('Facing park'))->states($yes_no_states);
        $form->switch('is_on_boulevard', __('On boulevard'))->states($yes_no_states);

        $form->divider('Price and Cost Information');

        $form->decimal('cash_price', __('Cash price'));
        $form->decimal('installment_price', __('Installment price'));
        $form->decimal('cost', __('Cost'));

        return $form;
    }
}
