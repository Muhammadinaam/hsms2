@extends('prints.layout')

@section('content')

    <?php
        $model = \App\Booking::find($entity_id);
    ?>

    <p class="title text-center">
        File Booking
    </p>

    <br>
    <div class="text-center">
        <span class="sub-title round-border bg-gray p1">
            {{ $model->propertyFile->marlas + 0 }} Marlas - {{ $model->propertyFile->propertyType->name }} Plot
        </span>
    </div>

    <table class="full-width">
        <tr>
            <td>File No.
                <b>
                    {{$model->propertyFile->file_number}}
                </b>
            </td>
            <td class="text-right">Registration No. <b>{{$model->id}}</b></td>
        </tr>
    </table>

    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Preference Location 10%
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Boulevard
                    <i class="{{ $model->propertyFile->is_on_boulevard == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                </td>
                <td>Facing Park
                    <i class="{{ $model->propertyFile->is_facing_park == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                </td>
                <td>Corner
                    <i class="{{ $model->propertyFile->is_corner == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                </td>
            </tr>
        </table>
    </div>

    <br>
    <div class="round-border p3" style="width: 45vw; float: left;">
        <span class="round-border bg-gray p1">
            Applicant Information
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Picture:</td> <td><img src="{{asset('uploads/'. $model->customer->picture)}}" style="max-width: 80px;"></td>
            </tr>
            <tr>
                <td>Name:</td> <td class="td-value">{{$model->customer->name}}</td>
            </tr>
            <tr>
                <td>Father Name:</td> <td class="td-value">{{$model->customer->father_name}}</td>
            </tr>
            <tr>
                <td>Husband Name:</td> <td class="td-value">{{$model->customer->husband_name}}</td>
            </tr>
            
            <tr>
                <td>CNIC/Passport:</td> <td class="td-value">{{$model->customer->cnic}}</td>
            </tr>
            <tr>
                <td>Email:</td> <td class="td-value">{{$model->customer->email}}</td>
            </tr>

            <tr>
                <td>Address:</td> <td class="td-value">{{$model->customer->address}}</td>
            </tr>
            <tr>
                <td>Phone:</td> <td class="td-value">{{$model->customer->phone}}</td>
            </tr>
        </table>
    </div>

    <div class="round-border p3" style="width: 45vw; float:left; margin-left: 1vw;">
        <span class="round-border bg-gray p1">
            Next of Kin Information
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Name:</td> <td class="td-value">{{$model->customer->kin_name}}</td>
            </tr>
            <tr>
                <td>Father Name:</td> <td class="td-value">{{$model->customer->kin_father_name}}</td>
            </tr>
            <tr>    
                <td>Husband Name:</td> <td class="td-value">{{$model->customer->kin_husband_name}}</td>
            </tr>
            
            <tr>
                <td>CNIC/Passport:</td> <td class="td-value">{{$model->customer->kin_cnic}}</td>
            </tr>
            <tr>
                <td>Email:</td> <td class="td-value">{{$model->customer->kin_email}}</td>
            </tr>

            <tr>
                <td>Address:</td> <td class="td-value">{{$model->customer->kin_address}}</td>
            </tr>
            <tr>
                <td>Phone:</td> <td class="td-value">{{$model->customer->kin_phone}}</td>
            </tr>
        </table>
    </div>

    <div style="clear: left;">
    </div>
    
    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Other Information
        </span>

        <div style="margin-top: 15px;">
            <p>
            Down Payment: <span class="value">{{$model->down_payment_received}}</span>,
            Form Processing Fee: <span class="value">{{$model->form_processing_fee_received}}</span>
            </p>
        </div>
        
    </div>

@endsection