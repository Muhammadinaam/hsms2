<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Instalments Due Report</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>File Number</th>
                    <th>Booking Date</th>
                    <th>Holder Name</th>
                    <th>Holder Phone</th>
                    <th>Type</th>
                    <th>Count</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report_data as $row)
                    <?php
                        $due_amount = $row->instalments_amount - $row->instalments_receipts_amount;
                        $count = $row->instalments_count - $row->instalments_receipts_count;
                        $count = $count < 0 ? '' : $count;
                        $booking = \App\Booking::where('status', '<>', '\App\Helpers\StatusesHelper::CANCELLED')->where('property_file_id', $row->property_file_id)->first();
                        $booking_date = $booking != null ? \Carbon\Carbon::parse($booking->date)->format('d-M-Y') : '';
                    ?>

                    @if($due_amount > 0)
                    <tr>
                        <td>{{$row->file_number}}</td>
                        <td>{{ $booking_date }}</td>
                        <td>{{$row->holder_name}}</td>
                        <td>{{$row->holder_phone}}</td>
                        <td>{{$row->payment_plan_type_name}}</td>
                        <td>{{$count}}</td>
                        <td>{{$due_amount}}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>