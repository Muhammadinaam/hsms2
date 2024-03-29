@component('components.report_header')
@endcomponent

<div style="font-size: 10px;" class="box box-default box-solid hidden-print">
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
                    @foreach(\App\PropertyFile::orderBy('file_number')->get() as $p_file)
                    <option value="{{$p_file->id}}" {{$p_file->id == request()->property_file ? 'selected' : ''}}> {{$p_file->file_number}}</option>
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

<h3>Customer Ledger</h3>

@if(isset($property_file))
<h4>File Number: {{$property_file->file_number}}</h4>
<div class="">
    <table style="font-size: 10px;" class="table table-bordered table-small table-sm table-condensed">
        <tr>
            <td>
                <table class="table">
                    <tr>
                        <td>
                            <img width="100px" src="{{$property_file->current_holder != null ? asset('uploads/' . $property_file->current_holder->picture) : ''}}">
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="table">
                    <tr>
                        <td>Name:</td>
                            <td>
                                {{$property_file->current_holder != null ? $property_file->current_holder->name : ''}}
                                @if($property_file->booking != null)
                                    &nbsp;({{$property_file->booking->main_partner_ratio}}%)
                                @endif
                            </td>
                        <td>Block:</td><td>{{$property_file->block != null ? $property_file->block->name : ''}}</td>
                    </tr>
                    <tr>
                        <td>Partners</td>
                        <td colspan="3">
                            @if($property_file->booking != null)
                                @foreach($property_file->booking->partnershipDetails as $partnershipDetails)
                                {{$partnershipDetails->partner->name}} ({{$partnershipDetails->ratio}}%), 
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Address:</td><td>{{$property_file->current_holder != null ? $property_file->current_holder->address : ''}}</td>
                        <td>Plot Number:</td><td>{{$property_file->property_number}}</td>
                    </tr>
                    <tr>
                        <td>Contact:</td><td>{{$property_file->current_holder != null ? $property_file->current_holder->phone : ''}}</td>
                        
                        <td>Street No:</td><td>{{$property_file->streetNo}}</td>
                        <td>Plot Size:</td><td>{{$property_file->marlas}}</td>
                    </tr>
                    <tr>
                        <td>Booking Date:</td><td>{{$property_file->booking != null ? \Carbon\Carbon::parse($property_file->booking->date)->format('d-M-Y') : ''}}</td>
                        <td>Dealer:</td><td>{{$property_file->current_dealer != null ? $property_file->current_dealer->business_name : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Property Type:</td><td>{{$property_file->propertyType != null ? $property_file->propertyType->name : ''}}</td>
                        <td>Preference Location:</td>
                            <td>
                                <span>Boulevard &nbsp;
                                    <i class="{{ $property_file->is_on_boulevard == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                                </span> &nbsp; &nbsp; &nbsp; &nbsp;
                                <span>Facing Park &nbsp;
                                    <i class="{{ $property_file->is_facing_park == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                                </span> &nbsp; &nbsp; &nbsp; &nbsp;
                                <span>Corner &nbsp;
                                    <i class="{{ $property_file->is_corner == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                                </span>
                            </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>
@endif

<p>
    <?php
        if(request()->property_file != '') 
        {
            $property_file = \App\PropertyFile::find(request()->property_file);
            $booking = \App\Booking::where('property_file_id', request()->property_file)
                ->where('status', '<>', \App\Helpers\StatusesHelper::CANCELLED)->first();
            $holder = $property_file->holder;
            $dealer = $property_file->dealer;
        }
    ?>
</p>

<table style="font-size: 10px;" class="table table-bordered table-small table-sm table-condensed">
    <thead>
        <th class="text-center">Instalment Type</th>
        <th class="text-center">Date</th>
        <th class="text-center">Due Date</th>
        <th class="text-center">Instalment Amount</th>
        <th class="text-center">Received Amount</th>
        <th class="text-center">Receipt Number</th>
        <th class="text-center">Balance</th>
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
        <td>{{isset($row['payment_plan_type']) ? $row['payment_plan_type'] : '' }}</td>
        <td>{{$row['date']->format('d-M-Y')}}</td>
        <td>{{isset($row['due_date']) ? $row['due_date']->format('d-M-Y') : ''}}</td>
        <td class="text-right">{{isset($row['amount']) ? number_format($row['amount'], 2) : ''}}</td>
        <td class="text-right">{{isset($row['receipt_amount']) ? number_format($row['receipt_amount'], 2) : ''}}</td>
        <td class="text-right">{{isset($row['receipt_numbers']) ? implode(', ', $row['receipt_numbers']) : '' }}</td>
        <td class="text-right">
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