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
        <table style="font-size: 10px;" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>File Number</th>
                    <th>Block</th>
                    <th>Property No.</th>
                    <th>Plot Size</th>
                    <th>Property Type</th>
                    <th>Booking Date</th>
                    <th>Price</th>
                    <th>Rebate</th>
                    <th class="text-right">Processing Fee</th>
                    <th class="text-right">Receipts</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_form_processing_fee_received = 0;
                    $total_instalment_receipts = 0;
                    $total_dealer_commission_amount = 0;
                    $total_sale_price = 0;
                ?>
                @foreach($report_data as $row)
                <tr>
                    <td class="text-center">{{$row->file_number}}</td>
                    <td>{{$row->block}}</td>
                    <td class="text-center">{{$row->property_number}}</td>
                    <td class="text-center">{{$row->marlas}}</td>
                    <td class="text-center">{{$row->property_type}}</td>
                    <td class="text-center">{{\Carbon\Carbon::parse($row->date)->format('d-M-Y')}}</td>
                    <td class="text-right">{{$row->booking_type == 'Cash' ? number_format($row->cash_price, 2) : number_format($row->installment_price, 2)}}</td>
                    <td class="text-right">{{number_format($row->dealer_commission_amount, 2)}}</td>
                    <td class="text-right">{{number_format($row->form_processing_fee_received, 2)}}</td>
                    <td>
                        <?php
                            $propertyFileInstalmentReceiptsTotal = 0;
                        ?>
                        @if(count($row->instalmentReceipts) > 0)
                            @foreach($row->instalmentReceipts as $instalmentReceipt)
                            <?php
                                $propertyFileInstalmentReceiptsTotal += $instalmentReceipt->amount;
                            ?>
                            @endforeach
                            <div class="text-right">
                                {{number_format($propertyFileInstalmentReceiptsTotal, 2)}}
                            </div>
                        <!-- <table class="table table-bordered">
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
                        </table> -->
                        @endif
                    </td>
                </tr>
                <?php
                    $total_form_processing_fee_received += $row->form_processing_fee_received;
                    $total_instalment_receipts += $propertyFileInstalmentReceiptsTotal;
                    $total_dealer_commission_amount += $row->dealer_commission_amount;
                    $total_sale_price += $row->booking_type == 'Cash' ? $row->cash_price : $row->installment_price;
                ?>
                @endforeach
                <tr style="font-weight: bold;" class="bg-info">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{number_format($total_sale_price, 2)}}</td>
                    <td class="text-right">{{number_format($total_dealer_commission_amount, 2)}}</td>
                    <td class="text-right">{{number_format($total_form_processing_fee_received, 2)}}</td>
                    <td class="text-right">{{number_format($total_instalment_receipts/2, 2)}}</td>
                </tr>
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>