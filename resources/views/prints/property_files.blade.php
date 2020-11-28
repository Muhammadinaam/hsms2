@extends('prints.layout')

@section('content')

    <?php
        $model = \App\PropertyFile::find($entity_id);
    ?>

    <p class="title text-center">
        File Information
    </p>

    <br>

    <table class="full-width">
        <tr>
            <td>File No.
                <b>
                    {{$model->file_number}} <br>
                </b>
                File Status: <b>{{ \App\Helpers\StatusesHelper::statusTitle($model->status)}}</b>
            </td>
            <td class="text-right">
                Plot Number: 
                <b>
                    @if($model->property_number != null)
                    {{$model->property_number}}
                    @else
                    _______
                    @endif
                </b> <br>
                Plot Size: 
                <b>
                    @if($model->marlas != null)
                    {{$model->marlas}}
                    @else
                    _______
                    @endif
                </b> <br>
                Block: 
                <b>
                    @if($model->block != null)
                    {{$model->block->name}}
                    @else
                    _______
                    @endif
                </b> <br>
            </td>
        </tr>
    </table>

    <br>
    <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Preference Location
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
    <div class="round-border p3" style="width: 45vw; float: left;">
        <span class="round-border bg-gray p1">
            File Holder Information
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td></td> 
                <td>
                    @if($model->holder_id != null)
                    <img src="{{asset('uploads/'. $model->holder->picture)}}" style="max-width: 80px;">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Name:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->name}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Father Name:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->father_name}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Husband Name:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->husband_name}}
                    @endif
                </td>
            </tr>
            
            <tr>
                <td>CNIC/Passport:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->cnic}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Email:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->email}}
                    @endif
                </td>
            </tr>

            <tr>
                <td>Address:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->address}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Phone:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->phone}}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="round-border p3" style="width: 45vw; float:left; margin-left: 1vw;">
        <span class="round-border bg-gray p1">
            Next of Kin Information
        </span>

        <table style="margin-top: 15px;" class="full-width">
            <tr>
                <td>Name:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_name}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Father Name:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_father_name}}
                    @endif
                </td>
            </tr>
            <tr>    
                <td>Husband Name:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_husband_name}}
                    @endif
                </td>
            </tr>
            
            <tr>
                <td>CNIC/Passport:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_cnic}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Email:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_email}}
                    @endif
                </td>
            </tr>

            <tr>
                <td>Address:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_address}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Phone:</td> 
                <td class="td-value">
                    @if($model->holder_id != null)
                    {{$model->holder->kin_phone}}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: left;">
    </div>
    
    <br>
    <p>
    This allotment is provisional and subject to the acceptance of the terms and conditions.
    <br><br>
    The Management of New Depalpur City Housing Scheme reserves the right to cancel / alter the location and dimensions until physical possession of the plot is handed over.
    </p>
    <!-- <div class="round-border p3">
        <span class="round-border bg-gray p1">
            Other Information
        </span>

        <div style="margin-top: 15px;">
            <p>
                File Status: <span class="value">{{ \App\Helpers\StatusesHelper::statusTitle($model->status)}}</span>,
            </p>
            <p>
                Property Type: 
                <span class="value">
                    @if($model->propertyType != null)
                    {{ $model->propertyType->name }}
                    @else
                    ________
                    @endif
                </span>,
            </p>
        </div>
        
    </div> -->

@endsection