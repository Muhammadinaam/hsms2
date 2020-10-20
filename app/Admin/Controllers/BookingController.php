<?php

namespace App\Admin\Controllers;

use App\Booking;
use App\Helpers\GeneralHelpers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BookingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bookings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Booking);

        $grid->column('date', __('Date'))->date('d-M-Y');
        $grid->column('customer.text_for_select', __('Customer'));
        $grid->column('propertyFile.text_for_select', __('Property File'));
        $grid->column('form_processing_fee_received', __('Form / Processing fee'));
        $grid->column('booking_type', __('Booking Type'));
        $grid->column('dealer.text_for_select', __('Dealer'));
        $grid->column('dealer_commission_amount', __('Dealer commission'));
        $grid->column('status', __('Booking Status'))->display(function ($status) {
            return \App\Helpers\StatusesHelper::statusTitle($status);
        })->filter([
            \App\Helpers\StatusesHelper::BOOKED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::BOOKED),
            \App\Helpers\StatusesHelper::ALLOTTED => \App\Helpers\StatusesHelper::statusTitle(\App\Helpers\StatusesHelper::ALLOTTED),
        ]);

        \App\Helpers\GeneralHelpers::setGridRowActions($grid, false, true, true, true);

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
        $show = new Show(Booking::findOrFail($id));

        $show->field('date', __('Date'));
        $show->field('customer_id', __('Customer id'));
        $show->field('customer_amount_received', __('Amount received'));
        $show->field('dealer_id', __('Dealer id'));
        $show->field('dealer_commission_amount', __('Dealer commission amount'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Booking);
        $id = isset(request()->route()->parameters()['booking']) ?
        request()->route()->parameters()['booking'] : null;
        $booking = \App\Booking::find($id);

        $form->saving(function (Form $form) use ($id, $booking) {

            if ($booking != null && !$booking->isEditableOrCancellable()) {
                return \App\Helpers\GeneralHelpers::ReturnJsonErrorResponse('Cannot Update', 'Status of Booking is [' . \App\Helpers\StatusesHelper::statusTitle($booking->status) . ']. It cannot be changed now.');
            }

            $new_property_file = \App\PropertyFile::find($form->property_file_id);
            $old_property_file = $booking != null ? $booking->propertyfile : null;

            if ($new_property_file->dealer_id != null &&
                $new_property_file->dealer_id != $form->dealer_id) {
                return \App\Helpers\GeneralHelpers::ReturnJsonErrorResponse('Dealer Not Correct', 'Please select dealer to which this File was assigned. i.e. [' . $new_property_file->dealer->text_for_select . ']');
            }

            if ($old_property_file != null) {
                $old_property_file->dealer_id = $old_property_file->sold_by_dealer_id;
                $old_property_file->sold_by_dealer_id = null;
                $old_property_file->holder_id = null;
                $old_property_file->property_number = null;
                $old_property_file->block_id = null;
                $old_property_file->save();
            }

            $this->updateNewPropertyFile($new_property_file, $form);
        });

        $form->saved(function (Form $form) {

            $model = $form->model();
            $property_file = $model->propertyFile;

            self::postToLedger($form->model());
        });

        $form->divider('Customer and File Information');

        $form->date('date', __('Date'))->default(date('Y-m-d H:i:s'))
            ->rules('required');

        $property_file_where = 'status = \'' . \App\Helpers\StatusesHelper::AVAILABLE . '\' ';
        if ($booking != null) {
            $property_file_where .= ' OR id = \'' . $booking->property_file_id . '\'';
        }

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form,
            'property_file_id',
            __('Property File'),
            '',
            '\App\PropertyFile',
            $property_file_where)
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form,
            'customer_id',
            __('Customer'),
            'admin/people/create',
            '\App\Person',
            'person_type = \'' . \App\Person::PERSON_TYPE_CUSTOMER . '\' ')
            ->rules('required');

        $form->divider('Property Information');

        $marlas_options = [];
        foreach (\App\PropertyMarla::all() as $propertyMarla) {
            $marlas_options[$propertyMarla->marlas] = $propertyMarla->marlas;
        }
        $form->select('marlas', __('Marlas'))
            ->options($marlas_options)
            ->rules('required');

        $form->text('property_number', __('Property Number'))->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form,
            'block_id',
            __('Block'),
            'admin/blocks/create',
            '\App\Block')
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form,
            'property_type_id',
            __('Property type'),
            'admin/property-types/create',
            '\App\PropertyType')
            ->rules('required');

        $yes_no_states = [
            'on' => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'No', 'color' => 'secondary'],
        ];

        $form->switch('is_farmhouse', __('Farmhouse'))->states($yes_no_states);

        $form->divider('Preference Location');
        $form->switch('is_corner', __('Corner'))->states($yes_no_states);
        $form->switch('is_facing_park', __('Facing park'))->states($yes_no_states);
        $form->switch('is_on_boulevard', __('On boulevard'))->states($yes_no_states);

        $form->divider('Price and Cost Information');

        $form->decimal('cash_price', __('Cash price'))->rules('required');
        $form->decimal('installment_price', __('Installment price'))->rules('required');
        $form->decimal('cost', __('Cost'))->rules('required');

        $form->divider('Booking Type');

        $form->select('booking_type', __('Booking Type'))
            ->rules('required')
            ->options(
                [
                    \App\Booking::BOOKING_TYPE_CASH => \App\Booking::BOOKING_TYPE_CASH,
                    \App\Booking::BOOKING_TYPE_INSTALLMENT => \App\Booking::BOOKING_TYPE_INSTALLMENT,
                ]
            );

        $form->divider('Form Processing Fee');

        $form->decimal('form_processing_fee_received', __('Form / Processing fee received'))
            ->rules('required');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form,
            'form_processing_fee_received_account_id',
            __('Form / Processing fee received account'),
            'admin/account-heads/create',
            '\App\AccountHead',
            'type = \'' . \App\AccountHead::CASH_BANK . '\'')
            ->help('Cash or Bank Account in which amount received will be debited')
            ->rules('required');

        $form->divider('Dealer / Commission Information');

        \App\Helpers\SelectHelper::buildAjaxSelect(
            $form,
            'dealer_id',
            __('Dealer'),
            'admin/people/create',
            '\App\Person',
            'person_type = \'' . \App\Person::PERSON_TYPE_DEALER . '\' ');

        $form->decimal('dealer_commission_amount', __('Dealer commission amount'));

        $form->hasMany('attachments', __('Attachments'), function (Form\NestedForm $form) {
            $form->text('attachment_description', __('Attachment Description'))->rules('required');
            $form->file('attachment', __('Attachment'))
                ->creationRules(['required'])
                ->updateRules([]);
        })->mode('table');

        /*
        $script = <<<SCRIPT
        $(document).ready(function(){
            $("[name='property_file_id']").change(function(e){
                alert(e.target.value);
            })
        })
        SCRIPT;
        Admin::script($script);
        */

        return $form;
    }

    public function updateNewPropertyFile($property_file, $form)
    {
        $property_file->marlas = $form->marlas;
        $property_file->property_type_id = $form->property_type_id;
        $property_file->is_farmhouse = $form->is_farmhouse == 'on';
        $property_file->is_corner = $form->is_corner == 'on';
        $property_file->is_facing_park = $form->is_facing_park == 'on';
        $property_file->is_on_boulevard = $form->is_on_boulevard == 'on';
        $property_file->cash_price = $form->cash_price;
        $property_file->installment_price = $form->installment_price;
        $property_file->cost = $form->cost;

        if ($property_file->dealer_id != null) {
            $property_file->sold_by_dealer_id = $property_file->dealer_id;
            $property_file->dealer_id = null;
        }
        $property_file->holder_id = $form->customer_id;

        $property_file->property_number = $form->property_number;
        $property_file->block_id = $form->block_id;

        $property_file->save();
    }

    public static function postToLedger(\App\Booking $model)
    {
        if ($model->id == null) {
            throw new \Exception("Booking not saved correctly", 1);
        }

        $project_id = $model->propertyFile->project_id;
        $phase_id = $model->propertyFile->phase_id;
        $ledger_id = \App\Ledger::insertOrUpdateLedger(
            $project_id,
            $phase_id,
            $model->date,
            \App\Ledger::CUSTOMER_BOOKING,
            $model->id
        );

        // DELETE OLD ENTRIES
        \App\LedgerEntry::where('ledger_id', $ledger_id)->delete();

        $file = \App\PropertyFile::find($model->property_file_id);
        $sale_price = $model->booking_type == \App\Booking::BOOKING_TYPE_INSTALLMENT ? $file->installment_price : $file->cash_price;

        if ($sale_price == 0) {
            throw new \Exception("Sale price is 0", 1);
        }

        //SALES ENTRY
        // FILE DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            null,
            $model->property_file_id,
            'Sales booked',
            $sale_price
        );

        // SALES CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_PROPERTY_SALES_INCOME)->id,
            null,
            null,
            'Sales booked',
            -$sale_price
        );

        // COST OF SALES ENTRY
        // COST ACCOUNT DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_PROPERTY_SALES_COST)->id,
            null,
            null,
            'Cost of Sales booked',
            $file->cost
        );

        // LAND COST CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_LAND_COST)->id,
            null,
            null,
            'Cost of Sales booked',
            -$file->cost
        );

        // FORM PROCESSING FEE ENTRY
        // FORM PROCESSING FEE RECEIVED ACCOUNT DEBIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            $model->form_processing_fee_received_account_id,
            null,
            null,
            'Form Processing Fee',
            $model->form_processing_fee_received
        );

        // FORM PROCESSING FEE INCOME CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_FORM_PROCESSING_FEE_INCOME)->id,
            null,
            null,
            'Form Processing Fee',
            -$model->form_processing_fee_received
        );

        // DEALER COMMISSION ENTRY
        // COMMISSION EXPENSE DEBIT
        $commission_amount = $model->dealer_commission_amount != null ? $model->dealer_commission_amount : 0;
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_DEALER_COMMISSION_EXPENSE)->id,
            null,
            null,
            'Dealer Commission',
            $commission_amount
        );

        // DEALER ACCOUNT CREDIT
        \App\Ledger::insertOrUpdateLedgerEntries(
            $ledger_id,
            \App\AccountHead::getAccountByIdt(\App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE)->id,
            $model->dealer_id,
            null,
            'Dealer Commission',
            -$commission_amount
        );

        // property inventory
        \App\PropertyInventoryLedger::where('entry_type', \App\PropertyInventoryLedger::BOOKING)
            ->where('entry_id', $model->id)
            ->delete();

        $propertyInventoryLedger = new \App\PropertyInventoryLedger();
        $propertyInventoryLedger->date = $model->date;
        $propertyInventoryLedger->entry_id = $model->id;
        $propertyInventoryLedger->entry_type = \App\PropertyInventoryLedger::BOOKING;
        $propertyInventoryLedger->project_id = $project_id;
        $propertyInventoryLedger->phase_id = $phase_id;
        $propertyInventoryLedger->remarks = 'Customer booking';
        $propertyInventoryLedger->marlas = $model->marlas;
        $propertyInventoryLedger->property_type_id = $model->property_type_id;
        $propertyInventoryLedger->is_farmhouse = $model->is_farmhouse;
        $propertyInventoryLedger->is_corner = $model->is_corner;
        $propertyInventoryLedger->is_facing_park = $model->is_facing_park;
        $propertyInventoryLedger->is_on_boulevard = $model->is_on_boulevard;
        $propertyInventoryLedger->quantity = -1;
        $propertyInventoryLedger->save();

    }
}
