<?php

namespace App\Admin\Controllers;

use App\LandPurchase;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LandPurchaseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Land Purchases';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LandPurchase);

        $grid->column('date', __('Date'))->date('d-M-Y');
        $grid->column('project.name', __('Project'));
        $grid->column('phase.name', __('Phase'));
        $grid->column('land_information', __('Land information'));
        $grid->column('purchase_document', __('Purchase document'))->downloadable();
        $grid->column('cost', __('Cost'));
        $grid->column('creditAccount.text_for_select', __('Credit account'));

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
        $show = new Show(LandPurchase::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('land_information', __('Land information'));
        $show->field('purchase_document', __('Purchase document'));
        $show->field('cost', __('Cost'));
        $show->field('credit_account_id', __('Credit account id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new LandPurchase);

        $form->saved(function(Form $form){
            self::postToLedger($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d'))->rules('required');

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

        $form->textarea('land_information', __('Land information'))
            ->rows(10)
            ->rules('required');

        $form->file('purchase_document', __('Purchase document'))
            ->rules('required')
            ->downloadable();

        $form->decimal('cost', __('Cost'))
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'credit_account_id', 
            __('Credit Account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            '')
            ->help('Account Head which will be credited with cost. Select Cash or Bank if land cost is paid through Cash or Bank. Otherwise select Loan account')
            ->rules('required');

        return $form;
    }

    public static function postToLedger(\App\LandPurchase $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Land Purchase not saved correctly", 1);   
        }

        $project_id = $model->project_id;
        $phase_id = $model->phase_id;
        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $model->date, 
            \App\Ledger::LAND_PURCHASE, 
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        // LAND COST DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_LAND_COST)->id,
            null,
            null,
            'Land Purchase Cost',
            $model->cost
        );

        // CREDIT ACCOUNT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->credit_account_id,
            null,
            null,
            'Land Purchase Cost',
            -$model->cost
        );
    }
}
