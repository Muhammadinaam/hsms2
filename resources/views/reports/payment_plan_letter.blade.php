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
    <?php
        if(request()->property_file != '') 
        {
            $property_file = \App\PropertyFile::find(request()->property_file);
            $holder = $property_file->holder;
            $dealer = $property_file->dealer;
        }
    ?>
    @if(isset($property_file))
    Property {{ $property_file->text_for_select }} <br>
        @if(isset($holder))
            Holder Name: {{ $holder->name }} <br>
            Holder Phone: {{ $holder->phone }} <br>
        @endif
        @if(isset($dealer))
            Dealer Name: {{ $dealer->name }} <br>
            Dealer Phone: {{ $dealer->phone }} <br>
        @endif
    @endif
</p>

<table class="table table-bordered">
    <thead>
        <th>Date</th>
        <th>Instalment Type</th>
        <th>Due Date</th>
        <th class="text-right">Instalment Amount</th>
        <th class="text-right">Received Amount</th>
        <th>Receipt Type</th>
        <th>Receipt Number</th>
        <th>Balance</th>
        <th class="hidden-print">Action</th>
    </thead>
    <?php
        $total_amount = 0;
        $total_receipt_amount = 0;
    ?>
    @foreach($report_data as $row)
    <?php
        $total_amount += isset($row['amount']) ? $row['amount'] : 0;
        $total_receipt_amount += isset($row['receipt_amount']) ? $row['receipt_amount'] : 0;
    ?>
    <tr>
        <td>{{$row['date']->format('d-M-Y')}}</td>
        <td>{{isset($row['instalment_payment_plan_type']) ? $row['instalment_payment_plan_type'] : '' }}</td>
        <td>{{isset($row['due_date']) ? $row['due_date']->format('d-M-Y') : ''}}</td>
        <td class="text-right">{{isset($row['amount']) ? number_format($row['amount'], 2) : ''}}</td>
        <td class="text-right">{{isset($row['receipt_amount']) ? number_format($row['receipt_amount'], 2) : ''}}</td>
        <td>{{isset($row['receipt_payment_plan_type']) ? $row['receipt_payment_plan_type'] : '' }}</td>
        <td>{{isset($row['receipt_number']) ? $row['receipt_number'] : '' }}</td>
        <td>
            {{number_format($total_amount - $total_receipt_amount, 2)}}
        </td>
        <td class="hidden-print">
            <button class="btn btn-default">
                Print Challan
            </button>
        </td>
    </tr>
    @endforeach
    <tr style="font-weight: bold;" class="bg-info">
        <td>Total</td>
        <td></td>
        <td></td>
        <td class="text-right">
            {{number_format($total_amount, 2)}}
        </td>
        <td class="text-right">
            {{number_format($total_receipt_amount, 2)}}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>

<script>
    $(document).ready(function(){
        $('[name="property_file"]').select2();
    })
</script>