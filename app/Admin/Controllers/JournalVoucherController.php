<?php

namespace App\Admin\Controllers;

use App\JournalVoucher;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class JournalVoucherController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Journal Voucher';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new JournalVoucher);

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date'));
        $grid->column('project_id', __('Project'));
        $grid->column('phase_id', __('Phase'));

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
        $show = new Show(JournalVoucher::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('project_id', __('Project'));
        $show->field('phase_id', __('Phase'));
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
        $form = new Form(new JournalVoucher);

        $form->saved(function(Form $form){
            self::postToLedger($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        
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

        $form->hasMany('journalVoucherDetails', __('Entries'), function (Form\NestedForm $form) {
        
            \App\Helpers\SelectHelper::buildAjaxSelect(
                $form, 
                'account_head_id', 
                __('Account Head'), 
                'admin/account-heads/create', 
                '\App\AccountHead')
                ->rules('required');
                
            $form->text('description', __('Description'))->rules('required');

            $form->decimal('debit', __('Debit'));
    
            $form->decimal('credit', __('Credit'));

            \App\Helpers\SelectHelper::buildAjaxSelect(
                $form, 
                'person_id', 
                __('Person (Optional)'), 
                '', 
                '\App\Person');

            \App\Helpers\SelectHelper::buildAjaxSelect(
                $form, 
                'property_file_id', 
                __('Property File (Optional)'), 
                '', 
                '\App\PropertyFile');


        })->mode('table');

        return $form;
    }

    public static function postToLedger(\App\JournalVoucher $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Journal Voucher not saved correctly", 1);   
        }

        $project_id = $model->project_id;
        $phase_id = $model->phase_id;
        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $model->date, 
            \App\Ledger::JOURNAL_VOUCHER,
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        // JOURNAL VOUCHER ENTRIES
        $total_debit = 0;
        $total_credit = 0;
        foreach($model->journalVoucherDetails as $detail)
        {
            $total_debit += $detail->debit;
            $total_credit += $detail->credit;

            \App\Ledger::insertOrUpdateLedgerEntries(
                $ledger_id,
                $detail->account_head_id,
                $detail->person_id,
                $detail->property_file_id,
                $detail->description,
                $detail->debit - $detail->credit
            );
        }

        if($total_debit != $total_credit)
        {
            throw new \Exception("Error Processing Request", 1);
        }
    }
}
