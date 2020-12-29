<?php

namespace App\Admin\Controllers;

use App\Person;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PersonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Persons';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Person);

        $grid->column('picture', __('picture'))->image();
        $grid->column('name', __('Name'));
        $grid->column('business_name', __('Business Name'));
        $grid->column('father_name', __('Father name'));
        $grid->column('husband_name', __('Husband name'));
        $grid->column('cnic', __('Cnic'));
        $grid->column('address', __('Address'));
        $grid->column('phone', __('Phone'));
        $grid->column('person_type', __('Person type'));
        $grid->column('kin_name', __('Kin name'));
        $grid->column('kin_father_name', __('Kin father name'));
        $grid->column('kin_husband_name', __('Kin husband name'));
        $grid->column('kin_cnic', __('Kin cnic'));
        $grid->column('kin_address', __('Kin address'));
        $grid->column('kin_phone', __('Kin phone'));

        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();
        
            // Add a column filter
            $filter->like('name', 'Name');
            $filter->like('business_name', 'Business Name');
            $filter->in('person_type', 'Person Type')->multipleSelect([
                \App\Person::PERSON_TYPE_DEALER => \App\Person::PERSON_TYPE_DEALER,
                \App\Person::PERSON_TYPE_CUSTOMER => \App\Person::PERSON_TYPE_CUSTOMER,
                \App\Person::PERSON_TYPE_SUPPLIER => \App\Person::PERSON_TYPE_SUPPLIER,
                \App\Person::PERSON_TYPE_EMPLOYEE => \App\Person::PERSON_TYPE_EMPLOYEE,
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
        $show = new Show(Person::findOrFail($id));

        $show->field('name', __('Name'));
        $show->field('system_id', __('System ID'));
        $show->field('father_name', __('Father name'));
        $show->field('husband_name', __('Husband name'));
        $show->field('cnic', __('Cnic'));
        $show->field('address', __('Address'));
        $show->field('phone', __('Phone'));
        $show->field('person_type', __('Person type'));
        $show->field('kin_name', __('Kin name'));
        $show->field('kin_father_name', __('Kin father name'));
        $show->field('kin_husband_name', __('Kin husband name'));
        $show->field('kin_cnic', __('Kin cnic'));
        $show->field('kin_address', __('Kin address'));
        $show->field('kin_phone', __('Kin phone'));
        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Person);

        $form->column(1/2, function ($form) {

            $form->divider('Person Information');

            $form->select('person_type', __('Person type'))
            ->rules('required')
            ->options(
                [
                    \App\Person::PERSON_TYPE_DEALER => \App\Person::PERSON_TYPE_DEALER,
                    \App\Person::PERSON_TYPE_CUSTOMER => \App\Person::PERSON_TYPE_CUSTOMER,
                    \App\Person::PERSON_TYPE_SUPPLIER => \App\Person::PERSON_TYPE_SUPPLIER,
                    \App\Person::PERSON_TYPE_EMPLOYEE => \App\Person::PERSON_TYPE_EMPLOYEE,
                ]
            );

            $form->text('system_id', __('System ID'))
            ->creationRules(['required', "unique:people"])
            ->updateRules(['required', "unique:people,system_id,{{id}}"]);

            $form->image('picture', __('Picture'));

            $form->text('name', __('Name'));
            $form->text('business_name', __('Business Name'));
            $form->text('father_name', __('Father name'));
            $form->text('husband_name', __('Husband name'));
            $form->text('cnic', __('Cnic'))->rules('required');
            $form->text('address', __('Address'))->rules('required');
            $form->mobile('phone', __('Phone'))->rules('required');
        });

        $form->column(1/2, function ($form) {

            $form->divider('Next of Kin Information');

            $form->text('kin_name', __('Kin name'));
            $form->text('kin_father_name', __('Kin father name'));
            $form->text('kin_husband_name', __('Kin husband name'));
            $form->text('kin_cnic', __('Kin cnic'));
            $form->text('kin_address', __('Kin address'));
            $form->text('kin_phone', __('Kin phone'));
        });
        
        return $form;
    }
}
