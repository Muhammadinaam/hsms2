<div class="box box-default box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        
        <form>
            <div class="form-group">
                <label>Select Account Head</label>
                <select class="form-control" name="account">
                    @foreach(\App\AccountHead::all() as $account)
                    <option value="{{$account->id}}" {{$account->id == request()->account ? 'selected' : ''}}>{{$account->name}} - [Type: {{$account->type}}]</option>
                    @endforeach
                </select>
            </div>

            <div class="text-right">
                <button class="btn btn-primary">Show Report</button>
            </div>
        </form>

    </div><!-- /.box-body -->
</div>

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Receivable / Payable Detail Report</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Entry Type</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                  $total_debit = 0;
                  $total_credit = 0;
                  $balance = 0; 
                ?>
                @foreach($report_data as $report_row)
                <?php 
                  $total_debit += $report_row->amount > 0 ? $report_row->amount: 0; 
                  $total_credit += $report_row->amount > 0 ? 0 : abs($report_row->amount);
                  $balance += $total_debit - $total_credit; 
                ?>
                <tr class="{{ $report_row->amount > 0 ? 'bg-info' : 'bg-danger' }}">
                    <td>{{ \Carbon\Carbon::parse($report_row->date)->format('d-M-Y') }}</td>
                    <td>{{ $report_row->entry_type }}</td>
                    <td>{{ $report_row->description }}</td>
                    <td>{{ $report_row->amount > 0 ? $report_row->amount : '' }}</td>
                    <td>{{ $report_row->amount > 0 ? '' : abs($report_row->amount) }}</td>
                    <td>{{ $balance }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;" class="bg-info">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>{{$total_debit}}</td>
                    <td>{{$total_credit}}</td>
                    <td>{{$balance}}</td>
                </tr>
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>