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
                    <th>Account</th>
                    <th>Account Type</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_debit = 0;
                    $total_credit = 0;
                ?>
                @foreach($report_data as $report_row)
                <?php
                    $total_debit += $report_row->amount > 0 ? abs($report_row->amount) : 0;
                    $total_credit += $report_row->amount > 0 ? 0 : abs($report_row->amount);
                ?>
                <tr>
                    <td>{{$report_row->account_head_name}}</td>
                    <td>{{$report_row->account_head_type}}</td>
                    <td class="text-right">{{$report_row->amount > 0 ? abs($report_row->amount) : ''}}</td>
                    <td class="text-right">{{$report_row->amount > 0 ? '' : abs($report_row->amount)}}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold" class="bg-success">
                    <td>Total</td>
                    <td></td>
                    <td class="text-right">{{$total_debit}}</td>
                    <td class="text-right">{{$total_credit}}</td>
                </tr>
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>