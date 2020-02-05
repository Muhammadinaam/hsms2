@extends('prints.layout')

@section('content')

    <?php
        $model = \App\PropertyFile::find($entity_id);
    ?>

    <p class="title text-center">
        File Booking
    </p>

    <br>
    <div class="text-center">
        <span class="sub-title round-border bg-gray p1">
            @if($model->marlas != null && $model->marlas != 0)
            {{ $model->marlas + 0 }} Marlas -
            @endif
            @if($model->propertyType != null)
            {{ $model->propertyType->name }} Plot
            @endif
        </span>
    </div>

    <table class="full-width">
        <tr>
            <td>File No.
                <b>
                    {{$model->file_number}}
                </b>
            </td>
            <td class="text-right">Registration No._________</td>
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
                    <i class="{{ $model->is_on_boulevard == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                </td>
                <td>Facing Park
                    <i class="{{ $model->is_facing_park == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                </td>
                <td>Corner
                    <i class="{{ $model->is_corner == 1 ? 'fa fa-check-square-o' : 'fa fa-minus-square-o' }}"></i>
                </td>
            </tr>
        </table>
    </div>

    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Applicant Information
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Name:</td> <td class="td-value"></td>
                <td>Father Name:</td> <td class="td-value"></td>
                <td>Husband Name:</td> <td class="td-value"></td>
            </tr>
            
            <tr>
                <td>CNIC/Passport:</td> <td colspan="2" class="td-value"></td>
                <td>Email:</td> <td colspan="2" class="td-value"></td>
            </tr>

            <tr>
                <td>Address:</td> <td colspan="2" class="td-value"></td>
                <td>Phone:</td> <td colspan="2" class="td-value"></td>
            </tr>
        </table>
    </div>

    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Next of Kin Information
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Name:</td> <td class="td-value"></td>
                <td>Father Name:</td> <td class="td-value"></td>
                <td>Husband Name:</td> <td class="td-value"></td>
            </tr>
            
            <tr>
                <td>CNIC/Passport:</td> <td colspan="2" class="td-value"></td>
                <td>Email:</td> <td colspan="2" class="td-value"></td>
            </tr>

            <tr>
                <td>Address:</td> <td colspan="2" class="td-value"></td>
                <td>Phone:</td> <td colspan="2" class="td-value"></td>
            </tr>
        </table>
    </div>

    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Required Documents
        </span>

        <p style="margin-top: 15px;">
            (a). 2 Passport Size Pictures (b). Valid CNIC copy of Applicant (c).Valid CNIC copy Next of Kin
        </p>
    </div>

    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Bank Details
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Instrument No.:</td> <td colspan="3" class="td-value"></td>
                <td>Branch:</td> <td class="td-value"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Instrument Date:</td> <td class="td-value"></td>
                <td>Instrument Amount:</td> <td class="td-value"></td>
            </tr>
        </table>
    </div>

@endsection