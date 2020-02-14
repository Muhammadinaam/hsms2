<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use DB;

class ReportController
{
    public function showLedger(Content $content)
    {
        $report_data = [];
        if(request()->account != '')
        {
            $report_data = DB::table('ledger_entries')
                ->leftJoin('account_heads', 'ledger_entries.account_head_id', '=', 'account_heads.id')
                ->leftJoin('ledgers', 'ledger_entries.ledger_id', '=', 'ledgers.id')
                ->select(
                    'ledgers.date',
                    'ledgers.entry_type',
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
                DB::raw( 'if(file_people.name is not null, file_people.name, people.name) as person_name' ),
                DB::raw( 'if(file_people.system_id is not null, file_people.system_id, people.system_id) as system_id' ),
                DB::raw( 'if(file_people.person_type is not null, file_people.person_type, people.person_type) as person_type' ),
                DB::raw( 'sum(ledger_entries.amount) as amount' )
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
        if(request()->person != '')
        {
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

        if(request()->person != '')
        {
            $report_data = $report_data->where('dealer_id', request()->person);
        }
        $report_data = $report_data->with('dealer')->get()->groupBy('dealer_id');

        return $content
            ->title('Dealers Files Report')
            ->description('Report of Files Given to Dealers')
            ->row(view('reports.dealers_files_report', compact('report_data')));
    }
}