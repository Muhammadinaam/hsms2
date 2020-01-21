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
                <label>Select Person</label>
                <select class="form-control" name="person">
                    @foreach(\App\Person::all() as $person)
                    <option value="{{$person->id}}" {{$person->id == request()->person ? 'selected' : ''}}>{{$person->name}} ({{$person->person_type}}) - [ID: {{$person->system_id}}]</option>
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
                    <th>Amount</th>
                    <th>Receivable / (Payable)</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_amount = 0; ?>
                @foreach($report_data as $report_row)
                <?php $total_amount += $report_row->amount; ?>
                <tr class="{{ $report_row->amount > 0 ? 'bg-info' : 'bg-danger' }}">
                    <td>{{ \Carbon\Carbon::parse($report_row->date)->format('d-M-Y') }}</td>
                    <td>{{$report_row->entry_type}}</td>
                    <td>{{$report_row->description}}</td>
                    <td>{{abs($report_row->amount)}}</td>
                    <td>{{ $report_row->amount > 0 ? 'Receivable' : 'Payable' }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;" class="bg-info">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>{{abs($total_amount)}}</td>
                    <td>{{ $total_amount > 0 ? 'Receivable' : 'Payable' }}</td>
                </tr>
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>