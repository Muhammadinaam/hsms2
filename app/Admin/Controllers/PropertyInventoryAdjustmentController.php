<?php

namespace App\Admin\Controllers;

use App\PropertyInventoryAdjustment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PropertyInventoryAdjustmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Property Inventory Adjustment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PropertyInventoryAdjustment);

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date'))->date('d-M-Y');
        $grid->column('project.name', __('Project'));
        $grid->column('phase.name', __('Phase'));
        $grid->column('remarks', __('Remarks'));
        $grid->column('marlas', __('Marlas'));
        $grid->column('propertyType.name', __('Property type'));
        $grid->column('is_farmhouse', __('Is farmhouse'))->bool();;
        $grid->column('is_corner', __('Is corner'))->bool();;
        $grid->column('is_facing_park', __('Is facing park'))->bool();;
        $grid->column('is_on_boulevard', __('Is on boulevard'))->bool();;
        $grid->column('quantity', __('Quantity'));

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
        $show = new Show(PropertyInventoryAdjustment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('marlas', __('Marlas'));
        $show->field('property_type_id', __('Property type id'));
        $show->field('is_farmhouse', __('Is farmhouse'));
        $show->field('is_corner', __('Is corner'));
        $show->field('is_facing_park', __('Is facing park'));
        $show->field('is_on_boulevard', __('Is on boulevard'));
        $show->field('quantity', __('Quantity'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PropertyInventoryAdjustment);

        $form->saved(function(Form $form){
            self::postToLedger($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d H:i:s'))
        ->rules('required');

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

        $yes_no_states = [
            'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'No', 'color' => 'secondary'],
        ];

        $form->text('remarks', __('Remarks'))->rules('required');

        $marlas_options = [];
        foreach (\App\PropertyMarla::all() as $propertyMarla) {
            $marlas_options[$propertyMarla->id] = $propertyMarla->marlas;
        }
        $form->select('marlas', __('Marlas'))
            ->options($marlas_options)
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'property_type_id', 
            __('Property type'), 
            'admin/property-types/create', 
            '\App\PropertyType')->rules('required');
        $form->switch('is_farmhouse', __('Is farmhouse'))->states($yes_no_states);
        $form->switch('is_corner', __('Is corner'))->states($yes_no_states);
        $form->switch('is_facing_park', __('Is facing park'))->states($yes_no_states);
        $form->switch('is_on_boulevard', __('Is on boulevard'))->states($yes_no_states);
        $form->decimal('quantity', __('Quantity'))->rules('required');

        return $form;
    }

    public static function postToLedger(\App\PropertyInventoryAdjustment $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Not saved correctly", 1);   
        }

        $project_id = $model->project_id;
        $phase_id = $model->phase_id;

        // DELETE OLD ENTRIES
        \App\PropertyInventoryLedger::where('entry_type', \App\PropertyInventoryLedger::PROPERTY_INVENTORY_ADJUSTMENT)
            ->where('entry_id', $model->id)
            ->delete();

        $propertyInventoryLedger = new \App\PropertyInventoryLedger();
        $propertyInventoryLedger->date = $model->date;
        $propertyInventoryLedger->entry_id = $model->id;
        $propertyInventoryLedger->entry_type = \App\PropertyInventoryLedger::PROPERTY_INVENTORY_ADJUSTMENT;
        $propertyInventoryLedger->project_id = $model->project_id;
        $propertyInventoryLedger->phase_id = $model->phase_id;
        $propertyInventoryLedger->remarks = $model->remarks;
        $propertyInventoryLedger->marlas = $model->marlas;
        $propertyInventoryLedger->property_type_id = $model->property_type_id;
        $propertyInventoryLedger->is_farmhouse = $model->is_farmhouse;
        $propertyInventoryLedger->is_corner = $model->is_corner;
        $propertyInventoryLedger->is_facing_park = $model->is_facing_park;
        $propertyInventoryLedger->is_on_boulevard = $model->is_on_boulevard;
        $propertyInventoryLedger->quantity = $model->quantity;
        $propertyInventoryLedger->save();
    }
}
