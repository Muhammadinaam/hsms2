@extends('prints.vouchers_layout')

@section('content')

    <?php
        $model = \App\InstalmentReceipt::find($entity_id);
        $copies = ['Customer Copy', 'Office Copy']
    ?>

    @foreach($copies as $copy)

    <br>
    <div class="text-right">
        <span class="p2 bg-gray round-border">{{$copy}}</span>
    </div>

    <br><br>
    <p class="title text-center">Instalment Receipt</p>
    <hr>

    <br><br>
    <table style="width: 100%">
        <tr>
            <td>
                <table class="padding" style="width: 100%;">
                    <tr>
                        <td>Date: </td> <td><b>{{\Carbon\Carbon::parse($model->date)->format('d-M-Y')}}</b></td>
                    </tr>
                    <tr>
                        <td>Project: </td> <td><b>{{$model->propertyFile->project->name}}</b></td>
                    </tr>
                    <tr>
                        <td>Phase: </td> <td><b>{{$model->propertyFile->phase->name}}</b></td>
                    </tr>
                    <tr>
                        <td>Instalment Receipt ID: </td> <td><b>{{$model->id}}</b></td>
                    </tr>
                    <tr>
                        <td>Bank Receipt Number: </td> <td><b>{{$model->receipt_number}}</b></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="padding" style="width: 100%;">
                    <tr>
                        <td>File Number: </td> <td><b>{{$model->propertyFile->file_number}}</b></td>
                    </tr>
                    <tr>
                        <td>Block: </td> <td><b>{{$model->propertyFile->block->name}}</b></td>
                    </tr>
                    <tr>
                        <td>Property Number: </td> <td><b>{{$model->propertyFile->property_number}}</b></td>
                    </tr>
                    <tr>
                        <td>Size: </td> <td><b>{{$model->propertyFile->marlas}}</b></td>
                    </tr>
                    <tr>
                        <td>Type: </td> <td><b>{{$model->propertyFile->propertyType->name}}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br><br>
    <p class="sub-title">Detail</p>

    <table class="padding" style="width: 100%">
        <thead>
            <tr class="tr-bordered">
                <th>Instalment Type</th>
                <th style="width: 20%" class="text-right">Amount</th>
            </tr>
        </thead>
        <?php
            $total = 0;
        ?>
        @foreach($model->instalmentReceiptDetails as $instalmentReceiptDetail)
        <?php
            $total += $instalmentReceiptDetail->amount;
        ?>
        <tr class="tr-bordered">
            <td>{{$instalmentReceiptDetail->paymentPlanType->name}}</td>
            <td class="text-right">{{$instalmentReceiptDetail->amount != '' ? number_format($instalmentReceiptDetail->amount, 2): ''}}</td>
        </tr>
        @endforeach
        <tr class="tr-bordered" style="font-weight: bold;">
            <td>Fine (if any)</td>
            <td class="text-right">{{number_format($model->fine_amount, 2)}}</td>
        </tr>
        <tr class="tr-bordered" style="font-weight: bold;">
            <td>Total Amount</td>
            <td class="text-right">{{number_format($total + $model->fine_amount, 2)}}</td>
        </tr>
    </table>

    <br>
    <p>
        Amount: <strong>{{strtoupper(\App\Helpers\GeneralHelpers::convertNumberToWord($total + $model->fine_amount))}} only</strong>
    </p>

    <br><br><br><br>

    <!-- <h4>
        
    </h4> -->

    <!-- <span style="border-top: 1px solid black; padding-top: 3px">
        Prepared By
    </span> -->

    <br><br><br>
    <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
    <div style="text-align: right;">
        <span style="border-top: 1px solid black; padding-top: 3px">
            Received By
        </span>
    </div>
    <div style='page-break-after:always'></div>
    @endforeach

@endsection