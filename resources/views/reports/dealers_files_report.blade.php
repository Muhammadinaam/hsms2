@component('components.report_header')
@endcomponent

<div class="box box-default box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        
        <form>
            <div class="form-group">
                <label>Select Person</label>
                <select class="form-control" name="person">
                    <option value="">-</option>
                    @foreach(\App\Person::where('person_type', \App\Person::PERSON_TYPE_DEALER)->get() as $person)
                    <option value="{{$person->id}}" {{$person->id == request()->person ? 'selected' : ''}}>{{$person->name}} ({{$person->person_type}}) - [ID: {{$person->system_id}}]</option>
                    @endforeach
                </select>
            </div>

            <div class="text-right">
                <button class="btn btn-primary">Show Report</button>
            </div>
        </form>

    </div><!-- /.box-body -->
</div>

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Dealers Files Report</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <!-- <th>Dealer</th> -->
                    <th>File Number</th>
                    <th>Block</th>
                    <th>Plot Size</th>
                    <th>Plot Number</th>
                    <th>Collection (Receipts)</th>
                    <th>Rebate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report_data as $dealer_id => $dealer_files)
                <tr class="bg-info">
                    <td colspan="100" class="text-center">
                        <h3>{{\App\Person::find($dealer_id)->name}}, {{\App\Person::find($dealer_id)->business_name}}</h3>
                        (Total Files: {{count($dealer_files)}})
                    </td>
                </tr>
                    @foreach($dealer_files as $dealer_file)
                    <tr>
                        <!-- <td>{{$dealer_file->dealer->text_for_select}}</td> -->
                        <td class="text-center">{{$dealer_file->file_number}}</td>
                        <td class="text-center">{{$dealer_file->block->name}}</td>
                        <td class="text-center">{{$dealer_file->marlas}}</td>
                        <td class="text-center">{{$dealer_file->property_number}}</td>
                        <td class="text-center">{{$dealer_file->total_installment_receipts}}</td>
                        <td class="text-center">{{$dealer_file->booking != null ? $dealer_file->booking->dealer_commission_amount : '-'}}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>