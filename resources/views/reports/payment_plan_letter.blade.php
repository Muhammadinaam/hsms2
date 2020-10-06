@component('components.report_header')
@endcomponent

<div class="box box-default box-solid hidden-print">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        
        <form>
            <div class="form-group">
                <label>Property File</label>
                <select class="form-control" name="property_file">
                    <option value="">-</option>
                    @foreach(\App\PropertyFile::orderBy('file_number')->get() as $property_file)
                    <option value="{{$property_file->id}}" {{$property_file->id == request()->property_file ? 'selected' : ''}}> {{$property_file->file_number}}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-right">
                <button class="btn btn-primary">Show</button>
            </div>
        </form>

    </div><!-- /.box-body -->
</div>

@if($message != '')
<p class="text-center">{{$message}}</p>
@endif

<h3>Payment Plan</h3>
<p>
    Property File: {{ request()->property_file != '' ? \App\PropertyFile::find(request()->property_file)->file_number : '' }}
</p>

<table class="table table-bordered">
    <thead>
        <th>Sr.</th>
        <th>Payment Type</th>
        <th>Date</th>
        <th class="text-right">Amount</th>
    </thead>
    <?php
        $total = 0;
    ?>
    @foreach($report_data as $row)
    <tr>
        <td>{{$loop->index + 1}}</td>
        <td>{{$row['payment_plan_type']}}</td>
        <td>{{$row['date']}}</td>
        <td class="text-right">{{number_format($row['amount'], 2)}}</td>
    </tr>
    <?php
        $total += $row['amount'];
    ?>
    @endforeach
    <tr style="font-weight: bold;" class="bg-info">
        <td>Total</td>
        <td></td>
        <td></td>
        <td class="text-right">
            {{number_format($total, 2)}}
        </td>
    </tr>
</table>