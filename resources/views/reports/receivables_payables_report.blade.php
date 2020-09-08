@component('components.report_header')
@endcomponent

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Trial Balance</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Person</th>
                    <th>System ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Receivable / (Payable)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report_data as $report_row)
                <tr class="{{ $report_row->amount > 0 ? 'bg-info' : 'bg-danger' }}">
                    <td>{{$report_row->person_name}}</td>
                    <td>{{$report_row->system_id}}</td>
                    <td>{{$report_row->person_type}}</td>
                    <td>{{abs($report_row->amount)}}</td>
                    <td>{{ $report_row->amount > 0 ? 'Receivable' : 'Payable' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>