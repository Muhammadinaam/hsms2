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
            {{ $model->marlas }} Marlas - {{ $model->propertyType->name }} Plot
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
                <td>Name:______________</td>
                <td>Father Name:________________</td>
            </tr>
        </table>
    </div>


@endsection