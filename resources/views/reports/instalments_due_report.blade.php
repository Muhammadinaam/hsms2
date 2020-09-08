@component('components.report_header')
@endcomponent

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
                <?php
                    $grouped_report_data = $report_data->groupBy('file_number');
                ?>
                @foreach($grouped_report_data as $file_number => $rows)
                    <?php $total_due_amount = 0; ?>
                    @foreach($rows as $row)
                        <?php
                            $due_amount = $row->instalments_amount - $row->instalments_receipts_amount;
                            $count = $row->instalments_count - $row->instalments_receipts_count;
                            $count = $count < 0 ? '' : $count;
                            $booking = \App\Booking::where('status', '<>', '\App\Helpers\StatusesHelper::CANCELLED')->where('property_file_id', $row->property_file_id)->first();
                            $booking_date = $booking != null ? \Carbon\Carbon::parse($booking->date)->format('d-M-Y') : '';
                        ?>

                        @if($due_amount > 0)
                        <?php $total_due_amount += $due_amount; ?>
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
                    <tr class="bg-info">
                        <td colspan=6>
                            <b>Total</b>
                        </td>
                        <td>
                            <b>{{$total_due_amount}}</b>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>