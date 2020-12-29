<?php

namespace App\Admin\Controllers;

use DB;
use Encore\Admin\Layout\Content;

class ReportController
{
    public function showLedger(Content $content)
    {
        $report_data = [];
        if (request()->account != '') {
            $report_data = DB::table('ledger_entries')
                ->leftJoin('account_heads', 'ledger_entries.account_head_id', '=', 'account_heads.id')
                ->leftJoin('ledgers', 'ledger_entries.ledger_id', '=', 'ledgers.id')
                ->select(
                    'ledgers.date',
                    'ledgers.entry_type',
                    'ledgers.entry_id',
                    'account_heads.name as account_head_name',
                    'account_heads.type as accoun_head_type',
                    'ledger_entries.description',
                    'ledger_entries.amount'
                )
                ->where('ledger_entries.account_head_id', '=', request()->account)
                ->orderBy('ledgers.date', 'asc')
                ->get();
        }

        return $content
            ->title('Ledger')
            ->description('Ledger Report')
            ->row(view('reports.ledger', compact('report_data')));
    }

    public function receivablesPayablesReport(Content $content)
    {
        $report_data = DB::table('ledger_entries')
            ->leftJoin('people', 'ledger_entries.person_id', '=', 'people.id')
            ->leftJoin('property_files', 'ledger_entries.property_file_id', 'property_files.id')
            ->leftJoin('people as file_people', 'property_files.holder_id', '=', 'file_people.id')
            ->select(
                DB::raw('if(file_people.name is not null, file_people.name, people.name) as person_name'),
                DB::raw('if(file_people.system_id is not null, file_people.system_id, people.system_id) as system_id'),
                DB::raw('if(file_people.person_type is not null, file_people.person_type, people.person_type) as person_type'),
                DB::raw('sum(ledger_entries.amount) as amount')
            )
            ->groupBy('person_name', 'system_id', 'person_type')
            ->whereNotNull('people.name')
            ->orWhereNotNull('file_people.name')
            ->get();

        return $content
            ->title('Receivables Payables Report')
            ->description('Receivables Payables Report')
            ->row(view('reports.receivables_payables_report', compact('report_data')));
    }

    public function receivablesPayablesDetailReport(Content $content)
    {
        $report_data = [];
        if (request()->person != '') {
            $report_data = DB::table('ledger_entries')
                ->leftJoin('people', 'ledger_entries.person_id', '=', 'people.id')
                ->leftJoin('property_files', 'ledger_entries.property_file_id', 'property_files.id')
                ->leftJoin('people as file_people', 'property_files.holder_id', '=', 'file_people.id')
                ->leftJoin('ledgers', 'ledger_entries.ledger_id', '=', 'ledgers.id')
                ->select(
                    'ledgers.date',
                    'ledgers.entry_type',
                    'ledger_entries.description',
                    'ledger_entries.amount'
                )
                ->where('people.id', '=', request()->person)
                ->orWhere('file_people.id', '=', request()->person)
                ->orderBy('ledgers.date', 'asc')
                ->get();
        }

        return $content
            ->title('Receivables Payables Detail Report')
            ->description('Detail Report')
            ->row(view('reports.receivables_payables_detail_report', compact('report_data')));
    }

    public function trialBalanceReport(Content $content)
    {
        $report_data = DB::table('ledger_entries')
            ->leftJoin('account_heads', 'ledger_entries.account_head_id', '=', 'account_heads.id')
            ->select(
                'account_heads.name as account_head_name',
                'account_heads.type as account_head_type',
                DB::raw('sum(amount) as amount')
            )
            ->groupBy('account_heads.name', 'account_heads.type')
            ->get();

        return $content
            ->title('Trial Balance')
            ->description('Trial Balance Report')
            ->row(view('reports.trial_balance_report', compact('report_data')));
    }

    public function dealersFilesReport(Content $content)
    {
        $report_data = new \App\PropertyFile();
        $dealer_id = request()->dealer;
        $file_number = request()->file_number;
        $property_marla = request()->property_marla;
        $property_number = request()->property_number;
        $block = request()->block;

        if ($dealer_id != '') {
            $report_data = $report_data->where('dealer_id', $dealer_id)
                ->orWhere('sold_by_dealer_id', $dealer_id);
        }

        if ($file_number != '') {
            $report_data = $report_data->where('file_number', 'like', '%'.$file_number.'%' );
        }

        if ($property_marla != '') {
            $report_data = $report_data->where('marlas', $property_marla);
        }

        if ($property_number != '') {
            $report_data = $report_data->where('property_number', $property_number);
        }

        if ($block != '') {
            $report_data = $report_data->where('block_id', $block);
        }

        $report_data = $report_data->get();

        // if ($dealer_id == '') {
        //     $report_data = [];
        // }

        return $content
            ->title('Dealers Files Report')
            ->description('Report of Files Given to Dealers')
            ->row(view('reports.dealers_files_report', compact('report_data', 'dealer_id')));
    }

    public function instalmentsDueReport(Content $content)
    {
        $now = \Carbon\Carbon::now()->addDays(1)->format('Y-m-d');

        $instalment_receipt_details = \DB::table('instalment_receipt_details')
            ->join('instalment_receipts', 'instalment_receipts.id', '=', 'instalment_receipt_details.instalment_receipt_id')
            ->select(
                'instalment_receipts.property_file_id',
                'instalment_receipt_details.payment_plan_type_id',
                \DB::raw('sum(instalment_receipt_details.amount) as amount'),
                \DB::raw('count(instalment_receipt_details.amount) as receipt_count'),
            )
            ->groupBy([
                'instalment_receipts.property_file_id',
                'instalment_receipt_details.payment_plan_type_id',
            ])
            ->where('instalment_receipts.date', '<=', $now);

        $report_data = \DB::table('property_files')
            ->leftJoin('people as holders', 'property_files.holder_id', '=', 'holders.id')
            ->leftJoin('people as dealers', 'property_files.dealer_id', '=', 'dealers.id')
            ->leftJoin('payment_plan_schedules', function ($join) use ($now) {
                $join->on('property_files.id', '=', 'payment_plan_schedules.property_file_id')
                    ->whereDate('payment_plan_schedules.date', '<=', $now);
            })
            ->leftJoinSub($instalment_receipt_details, 'instalment_receipt_details', function ($join) {
                $join->on('property_files.id', '=', 'instalment_receipt_details.property_file_id')
                    ->on('payment_plan_schedules.payment_plan_type_id', '=', 'instalment_receipt_details.payment_plan_type_id');
            })
            ->leftJoin('payment_plan_types', 'payment_plan_types.id', '=', 'payment_plan_schedules.payment_plan_type_id')
            ->select(
                'property_files.id as property_file_id',
                'property_files.file_number',
                'holders.name as holder_name',
                'holders.phone as holder_phone',
                'dealers.name as dealer_name',
                'dealers.phone as dealer_phone',
                'payment_plan_types.name as payment_plan_type_name',
                \DB::raw('count(payment_plan_schedules.amount) as instalments_count'),
                \DB::raw('sum(instalment_receipt_details.receipt_count) as instalments_receipts_count'),
                \DB::raw('sum(payment_plan_schedules.amount) as instalments_amount'),
                \DB::raw('sum(instalment_receipt_details.amount) as instalments_receipts_amount')
            )
            ->groupBy([
                'property_files.id',
                'payment_plan_types.name',
                'property_files.file_number',
                'holders.name',
                'holders.phone',
                'dealers.name',
                'dealers.phone',
            ])
            ->get();

        return $content
            ->title('Instalments Due Report')
            ->description('Report of Instalments due from plot holders')
            ->row(view('reports.instalments_due_report', compact('report_data')));
    }

    public function propertyFilesCollections(Content $content)
    {
        $report_data =
        \DB::table('property_files')
            ->leftJoin('bookings', 'bookings.property_file_id', '=', 'property_files.id')
            ->leftJoin('blocks', 'blocks.id', '=', 'property_files.block_id')
            ->leftJoin('property_types', 'property_types.id', '=', 'property_files.property_type_id')
            ->where('bookings.status', '<>', \App\Helpers\StatusesHelper::CANCELLED)
            ->select(
                'bookings.date',
                'bookings.property_file_id',
                'property_files.property_number',
                'blocks.name as block',
                'property_files.file_number',
                'property_files.marlas',
                'bookings.form_processing_fee_received',
                'bookings.dealer_commission_amount',
                'bookings.booking_type',
                'bookings.cash_price',
                'bookings.installment_price',
                'property_types.name as property_type'
            )
            ->get();

        foreach ($report_data as $row) {
            $row->instalmentReceipts = \DB::table('instalment_receipts')
                ->where('property_file_id', $row->property_file_id)
                ->join('instalment_receipt_details', 'instalment_receipt_details.instalment_receipt_id', '=', 'instalment_receipts.id')
                ->join('payment_plan_types', 'instalment_receipt_details.payment_plan_type_id', '=', 'payment_plan_types.id')
                ->select('payment_plan_types.name', 'instalment_receipt_details.amount')
                ->get();
        }

        return $content
            ->title('Property Files Collections')
            ->description('Property Files Collections')
            ->row(view('reports.property_files_collections', compact('report_data')));
    }

    public function paymentPlanLetter(Content $content)
    {
        $report_data = [];
        $message = '';
        $property_file = \App\PropertyFile::find(request()->property_file);

        if (request()->property_file != '') {
            $payment_plan = \App\PaymentPlan::where('property_file_id', request()->property_file)->first();

            if ($payment_plan == null) {
                $message = 'No payment plan attached with property file';
            } else {
                foreach ($payment_plan->paymentPlanDetails as $paymentPlanDetail) {
                    for ($i = 0; $i < $paymentPlanDetail->number_of_payments; $i++) {
                        $date = \Carbon\Carbon::parse($paymentPlanDetail->starting_date)
                            ->addDays($i * $paymentPlanDetail->days_between_each_payment);
                        $due_date = clone $date;
                        $due_date = $due_date->addDays($paymentPlanDetail->due_days);
                        $report_data[] = [
                            'payment_plan_type' => $paymentPlanDetail->paymentPlanType->name,
                            'date' => $date,
                            'due_date' => $due_date,
                            'amount' => $paymentPlanDetail->amount,
                            'receipt_amount' => 0,
                            'receipt_ids' => [],
                            'receipt_numbers' => [],
                        ];
                    }
                }

                $report_data = collect($report_data)->sortBy('date')->toArray();

                $instalment_receipts = \DB::table('instalment_receipts')
                    ->where('property_file_id', request()->property_file)
                    ->join('instalment_receipt_details', 'instalment_receipt_details.instalment_receipt_id', '=', 'instalment_receipts.id')
                    ->join('payment_plan_types', 'payment_plan_types.id', '=', 'instalment_receipt_details.payment_plan_type_id')
                    ->select(
                        'payment_plan_types.name as payment_plan_type',
                        'instalment_receipts.date',
                        'instalment_receipt_details.amount',
                        'instalment_receipts.id',
                        'instalment_receipts.receipt_number',
                    )
                    ->orderBy('instalment_receipts.date', 'asc')
                    ->get();

                foreach ($report_data as $report_data_index => $report_row) {
                    foreach ($instalment_receipts as $instalment_receipts_index => $instalment_receipt) {
                        $balance = $report_row['amount'] > $report_row['receipt_amount'];
                        if (
                            $report_row['payment_plan_type'] == $instalment_receipt->payment_plan_type &&
                            $balance > 0
                        ) {
                            $to_be_allocated = $balance < $instalment_receipt->amount ? $balance : $instalment_receipt->amount;
                            $report_data[$report_data_index]['receipt_amount'] += $to_be_allocated;
                            $instalment_receipts[$instalment_receipts_index]->amount -= $to_be_allocated;
                            if ($to_be_allocated > 0) {
                                $report_data[$report_data_index]['receipt_ids'][] = $instalment_receipt->id;
                                $report_data[$report_data_index]['receipt_numbers'][] = $instalment_receipt->receipt_number;
                            }
                        }
                    }
                }

            }
        } else {
            $message = 'Please select property file';
        }

        return $content
            ->title('Payment Plan Letter')
            ->description('Payment Plan Letter')
            ->row(view('reports.payment_plan_letter', compact('report_data', 'message', 'property_file')));
    }

    public function propertyInventory(Content $content)
    {
        return $content
            ->title('Payment Inventory')
            ->description('Payment Inventory')
            ->row(view('reports.property_inventory_report'));
    }
}
