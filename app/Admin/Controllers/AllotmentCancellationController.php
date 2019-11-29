<?php

namespace App\Admin\Controllers;

use App\AllotmentCancellation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Helpers\AllotmentStatusConstants;
use App\Helpers\PropertyStatusConstants;

class AllotmentCancellationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Allotment Cancellation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AllotmentCancellation);

        $grid->column('id', __('Id'));
        $grid->column('date_of_cancellation', __('Date of cancellation'))->date('d-M-Y');
        $grid->column('cancellation_reason', __('Cancellation Reason'));
        $grid->column('allotment_id', __('Allotment id'));
        $grid->column('customer_amount_returned', __('Customer amount returned'));
        $grid->column('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $grid->column('agent_commission_to_be_returned', __('Agent commission to be returned'));

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
        $show = new Show(AllotmentCancellation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_of_cancellation', __('Date of cancellation'));
        $show->field('allotment_id', __('Allotment id'));
        $show->field('customer_amount_returned', __('Customer amount returned'));
        $show->field('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $show->field('agent_commission_to_be_returned', __('Agent commission to be returned'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AllotmentCancellation);
        $id = isset(request()->route()->parameters()['allotment_cancellation']) ? 
            request()->route()->parameters()['allotment_cancellation'] : null;
        $allotment_cancellation = \App\AllotmentCancellation::find($id);

        $form->saving(function (Form $form) use ($id, $allotment_cancellation) {
            
            $allotment = \App\Allotment::find($form->allotment_id);
            $ret = UpdateHelpers::UpdateStatus(
                'Allotment',
                $allotment, 
                \App\Allotment::class,
                'allotment_status',
                $allotment->allotment_status != AllotmentStatusConstants::$allotted 
                && $allotment->allotment_status != AllotmentStatusConstants::$cancelled,
                $allotment_cancellation,
                'allotment_id',
                AllotmentStatusConstants::$allotted,
                AllotmentStatusConstants::$cancelled);

            if($ret !== true) {
                return $ret;
            }

            // In case of Edit of AllotmentCancellation, we will restore status of property of Old Allotment to allotted
            if($allotment_cancellation != null)
            {
                $old_allotment = $allotment_cancellation->allotment;
                if($old_allotment != null)
                {
                    $old_property = \App\Property::find($old_allotment->property_id);
                    if($old_property != null && $old_property->property_status != PropertyStatusConstants::$available)  //allotment cancellation sets property status = available
                    {
                        $error = new MessageBag([
                            'title'   => 'Error',
                            'message' => 'Status of property of previous allotment is ['.$old_property->property_status.']. It cannot be changed now.',
                        ]);
                        return back()->with(compact('error'));
                    }
                    else
                    {
                        //restore old property status
                        $old_property->property_status = PropertyStatusConstants::$allotted;
                    }
                }
            }

            // Property specified in allotment to be cancelled should have status 'Allotted'. Otherwise throw error
            $new_property = \App\Allotment::find($form->allotment_id)->property;
            if($new_property != null && $new_property->property_status != PropertyStatusConstants::$allotted)
            {
                $error = new MessageBag([
                    'title'   => 'Error',
                    'message' => 'Status of property of allotment is ['.$new_property->property_status.']. It cannot be changed now.',
                ]);
                return back()->with(compact('error'));
            }
            else
            {
                // set new status
                $new_property->property_status = PropertyStatusConstants::$available;
            }

        });

        $form->date('date_of_cancellation', __('Date of cancellation'))->default(date('Y-m-d'));
        $form->text('cancellation_reason', __('Cancellation Reason'));
        
        $allotment_where = 'allotment_status = \''. AllotmentStatusConstants::$allotted .'\'';
        $allotment_where .=  $id != null ? ' OR allotments.id = ' . $allotment_cancellation->booking_id : '';
        $form->select('allotment_id', __('Allotment'))
        ->addVariables(['add_button_url' => ''])
        ->options(function ($id) {
            return \App\Helpers\SelectHelper::selectedOptionData('\App\Allotment', $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl('\App\Allotment', $allotment_where), 'id', 'text_for_select');
        
        $form->decimal('customer_amount_returned', __('Customer amount returned'));
        $form->number('customer_amount_returned_account_id', __('Customer amount returned account id'));
        $form->decimal('agent_commission_to_be_returned', __('Agent commission to be returned'));

        return $form;
    }
}
