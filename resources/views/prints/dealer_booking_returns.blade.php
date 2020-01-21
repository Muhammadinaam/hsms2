@extends('prints.layout')

@section('content')

    <?php
        $model = \App\DealerBookingReturn::find($entity_id);
    ?>

    <p class="title text-center">
        Dealer Files Booking Returns
    </p>

    
    <table style="margin-top: 15px;" class="full-width">
        <thead>
            <tr>
                <th colspan="100" style="text-align: left;">
    
                    <div class="round-border p3" style="font-weight: normal;">

                        <span style="font-weight: bold;" class="round-border bg-gray p1">
                            Booking Return Information
                        </span>
                        <br><br>

                        <p>
                            Date: <span class="value">{{\Carbon\Carbon::parse($model->date)->format('d-M-Y')}}</span>, &nbsp;&nbsp;&nbsp;
                            Booking Return No. <span class="value">{{$model->id}}</span>, &nbsp;&nbsp;&nbsp;
                            Token Returned: <span class="value">{{$model->dealer_amount_returned}}</span>, &nbsp;&nbsp;&nbsp;
                        </p>

                        <br><br>
                        <p>
                            Officer: <span class="value">{{$model->createdBy->name}}</span>, &nbsp;&nbsp;&nbsp;
                            Signature: 
                            <span class="value">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                        </p>


                        <br><br>
                        <span style="font-weight: bold;" class="round-border bg-gray p1">
                            Dealer Information
                        </span>

                        <br><br>
                        <p>
                            Dealer ID: <span class="value">&nbsp;&nbsp;{{$model->dealer->system_id}}&nbsp;&nbsp;</span>,&nbsp;&nbsp;&nbsp;&nbsp;
                            Dealer Name: <span class="value">&nbsp;&nbsp;{{$model->dealer->name}}&nbsp;&nbsp;</span>,&nbsp;&nbsp;&nbsp;&nbsp;
                            Father Name: <span class="value">&nbsp;&nbsp;{{$model->dealer->father_name}}&nbsp;&nbsp;</span>,&nbsp;&nbsp;&nbsp;&nbsp;
                        </p>
                        <br>
                        <p>
                        CNIC: <span class="value">&nbsp;&nbsp;{{$model->dealer->cnic}}&nbsp;&nbsp;</span>,&nbsp;&nbsp;&nbsp;&nbsp;
                        Phone: <span class="value">&nbsp;&nbsp;{{$model->dealer->phone}}&nbsp;&nbsp;</span>,&nbsp;&nbsp;&nbsp;&nbsp;
                        </p>
                        <br>
                        <p>
                        Address: <span class="value">&nbsp;&nbsp;{{$model->dealer->address}}&nbsp;&nbsp;</span>,&nbsp;&nbsp;&nbsp;&nbsp;
                        </p>
                        <br><br>
                        <p>
                        Dealer Signature: 
                            <span class="value">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                        </p>

                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="100">
                    <h1>Files Returned</h1>
                </th>
            </tr>
            
            <tr class="tr-bordered">
                <th>File No.</th>
                <th>Type</th>
                <th>Marlas</th>
                <th>Farm House</th>
            </tr>
        </thead>

        <tbody>
            @foreach($model->dealerBookingReturnDetails as $detail)
            <tr class="tr-bordered" style="text-align: center;">
                <td>{{$detail->propertyFile->file_number}}</td>
                <td>{{$detail->propertyFile->propertyType->name}}</td>
                <td>{{$detail->propertyFile->marlas}}</td>
                <td>{{ $detail->propertyFile->is_farmhouse == '1' ? 'Yes' : 'No'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection