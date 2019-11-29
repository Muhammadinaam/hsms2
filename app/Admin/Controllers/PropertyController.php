<?php

namespace App\Admin\Controllers;

use App\Property;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\SelectHelper;
use App\Helpers\PropertyStatusConstants;

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

        $grid->column('project.name', __('Project'));
        $grid->column('phase.name', __('Phase'));
        $grid->column('block.name', __('Block'));
        $grid->column('propertyType.name', __('Property Type'));
        $grid->column('name', __('Name'));
        $grid->column('marlas', __('Marlas'));
        $grid->column('is_corner', __('Corner'))->bool();
        $grid->column('is_facing_park', __('Facing Park'))->bool();
        $grid->column('is_on_boulevard', __('On Boulevard'))->bool();
        $grid->column('cash_price', __('Cash price'));
        $grid->column('installment_price', __('Installment price'));
        $grid->column('property_status', __('Property Status'));

        $grid->actions(function ($actions) {
            $actions->disableView();
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
        $show = new Show(Property::findOrFail($id));

        $show->field('project.name', __('Project id'));
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
        $id = isset(request()->route()->parameters()['property']) ? 
            request()->route()->parameters()['property'] : null;
        $property = \App\Property::find($id);

        $form->saving(function (Form $form) use ($property) {
            
            $ret = UpdateHelpers::isUpdateAllowed('Property', $property, 'property_status', PropertyStatusConstants::$available);
            if($ret !== true)
            {
                return GeneralHelpers::RedirectBackResponseWithError('Error', 'Status of Property is ['.$property->property_status.']. It cannot be changed now.');
            }
        });

        $form->select('project_id', 'Project')
        ->addVariables(['add_button_url' => 'admin/projects/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Project', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Project'), 'id', 'text_for_select');

        $form->select('phase_id', __('Phase'))
        ->addVariables(['add_button_url' => 'admin/phases/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Phase', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Phase'), 'id', 'text_for_select');

        $form->select('block_id', __('Block'))
        ->addVariables(['add_button_url' => 'admin/blocks/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Block', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Block'), 'id', 'text_for_select');
        
        $form->select('property_type_id', __('Property type'))
        ->addVariables(['add_button_url' => 'admin/property-types/create'])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\PropertyType', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\PropertyType'), 'id', 'text_for_select');

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
