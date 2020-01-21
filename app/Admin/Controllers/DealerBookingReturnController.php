<?php

namespace App\Admin\Controllers;

use App\DealerBookingReturn;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DealerBookingReturnController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dealer Booking Returns';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DealerBookingReturn);

        $grid->column('date', __('Date'));
        $grid->column('dealer.text_for_select', __('Dealer'));
        $grid->column('return_reason', __('Return Reason'));
        $grid->column('dealer_amount_returned', __('Dealer amount returned'));
        $grid->column('dealerAmountReturnedAccount.text_for_select', __('Dealer amount returned account'));

        \App\Helpers\GeneralHelpers::setGridRowActions($grid, false, false, true, true);

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
        $show = new Show(DealerBookingReturn::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('dealer_id', __('Dealer id'));
        $show->field('dealer_amount_returned', __('Dealer amount returned'));
        $show->field('dealer_amount_returned_account_id', __('Dealer amount returned account id'));
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
        $form = new Form(new DealerBookingReturn);
        $id = isset(request()->route()->parameters()['dealer_booking_return']) ? 
            request()->route()->parameters()['dealer_booking_return'] : null;
        $dealer_booking_return = \App\DealerBookingReturn::find($id);

        $form->saving(function(Form $form) use ($dealer_booking_return) {
            if($dealer_booking_return != null )
            {
                foreach($dealer_booking_return->dealerBookingReturnDetails as $detail)
                {
                    $property_file = $detail->propertyFile;
                    $property_file->dealer_id = $dealer_booking_return->dealer_id;
                    $property_file->save();
                }
            }
        });

        $form->saved(function (Form $form) {
            
            if( ! is_array(request()->dealerBookingReturnDetails) || count(request()->dealerBookingReturnDetails) == 0 )
            {
                throw new \Exception("Please select Property Files", 1);
            }
            
            foreach(request()->dealerBookingReturnDetails as $detail)
            {
                if($detail['_remove_'] != '1')
                {
                    $property_file_id = $detail['property_file_id'];

                    $property_file = \App\PropertyFile::find($property_file_id);
                    $property_file->dealer_id = null;
                    $property_file->save();
                }
            }

            self::postToLedger($form->model());
        });

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        
        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form, 
            'dealer_id', 
            __('Dealer'), 
            'admin/people/create', 
            '\App\Person',
            'person_type = \'' .\App\Person::PERSON_TYPE_DEALER. '\' ')
            ->rules('required');
        
        $form->text('return_reason', 'Return Reason')
        ->rules('required');

        $form->decimal('dealer_amount_returned', __('Dealer amount returned'))
            ->rules('required');

            \App\Helpers\SelectHelper::buildAjaxSelect(
                $form, 
                'dealer_amount_returned_account_id', 
                __('Dealer amount returned account'), 
                'admin/account-heads/create', 
                '\App\AccountHead',
                'type = \''. \App\AccountHead::CASH_BANK .'\'')
                ->help('Account Head in which amount received will be debited')
                ->rules('required');

        $form->hasMany('dealerBookingReturnDetails', __('Files Returned'), function (Form\NestedForm $form) use ($dealer_booking_return) {
    
            $property_file_where = '  ';

            $form->select('property_file_id', __('Property File'))->options(function ($id) {
                    $property_file = \App\PropertyFile::find($id);
                
                    if ($property_file) {
                        return [$property_file->id => $property_file->text_for_select];
                    }
                })->ajax('function(){
                    var dealer_id = $(\'select[name="dealer_id"]\').val();
                    if(dealer_id == "")
                    {
                        alert("Please select Dealer first");
                        return "no-url";
                    }
                    else
                    {
                        return "'. url("select-data-model?model=" . urlencode("\App\PropertyFile") ) . '&where_clauses=dealer_id=" + dealer_id
                    }
                }', 'id', 'text_for_select');

        })->mode('table');

        return $form;
    }

    public static function postToLedger(\App\DealerBookingReturn $model)
    {
        if($model->id == null)
        {
            throw new \Exception("Dealer Booking Return not saved correctly", 1);   
        }

        $project_id = null;
        $phase_id = null;
        foreach($model->dealerBookingReturnDetails as $detail) 
        {
            // TODO - improve this
            $file_project_id = $detail->propertyFile->project_id; 
            $file_phase_id = $detail->propertyFile->phase_id; 
            // TODO - improve this

            if($project_id == null)
            {
                
                $project_id = $file_project_id;
            }
            else
            {
                if($project_id != $file_project_id) 
                {
                    throw new \Exception("All Files should be related to same Project", 1);
                }
            }

            if($phase_id == null)
            {
                
                $phase_id = $file_phase_id;
            }
            else
            {
                if($phase_id != $file_phase_id) 
                {
                    throw new \Exception("All Files should be related to same Phase", 1);
                }
            }
        }

        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id, 
            $phase_id, 
            $model->date, 
            \App\Ledger::DEALER_BOOKING_RETURN, 
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        // DEALER ACCOUNT DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            $model->dealer_id,
            null,
            'Amount returned to Dealer against Files Booking',
            $model->dealer_amount_returned
        );

        // CASH / BANK CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->dealer_amount_returned_account_id,
            null,
            null,
            'Amount returned to Dealer against Files Booking',
            -$model->dealer_amount_returned
        );
    }
}
