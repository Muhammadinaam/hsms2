<?php

namespace App\Admin\Controllers;

use App\InstalmentReceipt;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InstalmentReceiptController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Instalment Receipt';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InstalmentReceipt);

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date'));
        $grid->column('property_file_id', __('Property file id'));
        $grid->column('description', __('Description'));
        $grid->column('amount', __('Amount'));
        $grid->column('created_by', __('Created by'));
        $grid->column('updated_by', __('Updated by'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(InstalmentReceipt::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('property_file_id', __('Property file id'));
        $show->field('description', __('Description'));
        $show->field('amount', __('Amount'));
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
        $form = new Form(new InstalmentReceipt);

        $form->saved(function(Form $form) {
            self::postToLedger($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d H:i:s'))->rules('required');
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'property_file_id', 
            __('Property File'), 
            '', 
            '\App\PropertyFile')
            ->rules('required');
        $form->text('description', __('Description'));
        $form->decimal('amount', __('Amount'));
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'amount_received_account_id', 
            __('Amount received account'), 
            'admin/account-heads/create', 
            '\App\AccountHead',
            'type = \''. \App\AccountHead::CASH_BANK .'\'')
            ->help('Cash or Bank Account in which amount received will be debited')
            ->rules('required');

        return $form;
    }

    public static function postToLedger(\App\InstalmentReceipt $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Not saved correctly", 1);   
        }

        $project_id = $model->propertyFile->project_id;
        $phase_id = $model->propertyFile->phase_id;
        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $model->date, 
            \App\Ledger::INSTALMENT_RECEIPT, 
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        // DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->amount_received_account_id,
            null,
            null,
            'Instalment received - ' . $model->description,
            +$model->amount
        );

        //  CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            null,
            $model->property_file_id,
            'Instalment received - ' . $model->description,
            -$model->amount
        );
    }
}
