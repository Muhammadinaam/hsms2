@component('components.report_header')
@endcomponent

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Property Files Collections</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>File Number</th>
                    <th>Property Number</th>
                    <th>Marlas</th>
                    <th>Booking Date</th>
                    <th>Dealer Commission</th>
                    <th class="text-right">Form Processing Fee Received</th>
                    <th class="text-right">Other Receipts</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_form_processing_fee_received = 0;
                    $total_instalment_receipts = 0;
                    $total_dealer_commission_amount = 0;
                ?>
                @foreach($report_data as $row)
                <tr>
                    <td>{{$row->file_number}}</td>
                    <td>{{$row->property_number}}</td>
                    <td>{{$row->marlas}}</td>
                    <td>{{\Carbon\Carbon::parse($row->date)->format('d-M-Y')}}</td>
                    <td>{{number_format($row->dealer_commission_amount, 2)}}</td>
                    <td class="text-right">{{number_format($row->form_processing_fee_received, 2)}}</td>
                    <td>
                        <?php
                            $propertyFileInstalmentReceiptsTotal = 0;
                        ?>
                        @if(count($row->instalmentReceipts) > 0)
                        <table>
                            @foreach($row->instalmentReceipts as $instalmentReceipt)
                            <?php
                                $propertyFileInstalmentReceiptsTotal += $instalmentReceipt->amount;
                            ?>
                            <tr>
                                <td>{{$instalmentReceipt->name}}</td>
                                <td class="text-right">{{number_format($instalmentReceipt->amount, 2)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <b>Total</b>
                                </td>
                                <td class="text-right">
                                    {{number_format($propertyFileInstalmentReceiptsTotal, 2)}}
                                </td >
                            </tr>
                        </table>
                        @endif
                    </td>
                </tr>
                <?php
                    $total_form_processing_fee_received += $row->form_processing_fee_received;
                    $total_instalment_receipts += $propertyFileInstalmentReceiptsTotal;
                    $total_dealer_commission_amount += $row->dealer_commission_amount;
                ?>
                @endforeach
                <tr style="font-weight: bold;" class="bg-info">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($total_dealer_commission_amount, 2)}}</td>
                    <td class="text-right">{{number_format($total_form_processing_fee_received, 2)}}</td>
                    <td class="text-right">{{number_format($total_instalment_receipts, 2)}}</td>
                </tr>
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>