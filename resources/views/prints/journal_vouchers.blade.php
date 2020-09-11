@extends('prints.vouchers_layout')

@section('content')

    <?php
        $model = \App\JournalVoucher::find($entity_id);
    ?>

    <br><br>
    <p class="title text-center">Journal Voucher</p>

    <br><br>
    <table class="padding" style="width: 50%;">
        <tr>
            <td>Date: </td> <td><b>{{\Carbon\Carbon::parse($model->date)->format('d-M-Y')}}</b></td>
        </tr>
        <tr>
            <td>Project: </td> <td><b>{{$model->project->name}}</b></td>
        </tr>
        <tr>
            <td>Phase: </td> <td><b>{{$model->phase->name}}</b></td>
        </tr>
        <tr>
            <td>JV ID: </td> <td><b>{{$model->id}}</b></td>
        </tr>
    </table>

    <br><br>
    <p class="sub-title">Entries</p>

    <table class="full-width padding">
        <thead>
            <tr class="tr-bordered">
                <th>Account Head</th>
                <th>Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
                <th>Person</th>
                <th>Property File</th>
            </tr>
        </thead>
        <?php
            $totalDebit = 0;
            $totalCredit = 0;
        ?>
        @foreach($model->journalVoucherDetails as $journalVoucherDetail)
        <?php
            $totalDebit += $journalVoucherDetail->debit;
            $totalCredit += $journalVoucherDetail->credit;
        ?>
        <tr class="tr-bordered">
            <td>{{$journalVoucherDetail->accountHead->name}}</td>
            <td>{{$journalVoucherDetail->description}}</td>
            <td class="text-right">{{$journalVoucherDetail->debit != '' ? number_format($journalVoucherDetail->debit, 2): ''}}</td>
            <td class="text-right">{{$journalVoucherDetail->credit != '' ? number_format($journalVoucherDetail->credit, 2): ''}}</td>
            <td>{{$journalVoucherDetail->person != null ? $journalVoucherDetail->person->name : ''}}</td>
            <td>{{$journalVoucherDetail->propertyFile != null ? $journalVoucherDetail->propertyFile->file_number : ''}}</td>
        </tr>
        @endforeach
        <tr class="tr-bordered" style="font-weight: bold;">
            <td colspan="2">Total</td>
            <td class="text-right">{{number_format($totalDebit, 2)}}</td>
            <td class="text-right">{{number_format($totalCredit, 2)}}</td>
            <td colspan="2"></td>
        </tr>
    </table>

    <br><br><br><br>

    <!-- <span style="border-top: 1px solid black; padding-top: 3px">
        Prepared By
    </span> -->

    <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
    <span style="border-top: 1px solid black; padding-top: 3px">
        Received By
    </span>

@endsection