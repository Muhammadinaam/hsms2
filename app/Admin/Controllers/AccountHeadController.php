<?php

namespace App\Admin\Controllers;

use App\AccountHead;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AccountHeadController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Account Heads';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AccountHead);

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('type', __('Type'));

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
        $show = new Show(AccountHead::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('type', __('Type'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AccountHead);

        $id = isset(request()->route()->parameters()['account_head']) ? 
            request()->route()->parameters()['account_head'] : null;
        $account_head = \App\AccountHead::find($id);
        if($account_head != null && $account_head->is_reserved == 1) 
        {
            return 
            '<div class="alert alert-danger">This Account is Reserved Account and cannot be edited</div>';
        }

        $form->text('name', __('Name'));

        $account_types = [];
        foreach(\App\AccountHead::ACCOUNT_TYPES as $type){
            $account_types[$type] = $type;
        }
        $form->select('type', __('Type'))->options($account_types);

        return $form;
    }
}
