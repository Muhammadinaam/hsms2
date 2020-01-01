<?php

namespace App\Admin\Controllers;

use App\File;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FileController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Files';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new File);

        $grid->column('project.name', __('Project'));
        $grid->column('phase.name', __('Phase'));
        
        $grid->column('file_number', __('File number'));
        $grid->column('marlas', __('Marlas'));
        $grid->column('property_id', __('Property id'));
        $grid->column('dealer_id', __('Dealer id'));
        $grid->column('holder_id', __('Holder id'));
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
        $show = new Show(File::findOrFail($id));

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
        $form = new Form(new File);

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

        $form->text('file_number', __('File number'));
        $form->decimal('marlas', __('Marlas'));

        return $form;
    }
}
