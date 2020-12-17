@component('components.report_header')
@endcomponent

<div class="box box-default box-solid hidden-print">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body">
        
        <form>
            <div class="form-group">
                <label>Select Dealer</label>
                <select class="form-control" name="dealer">
                    <option value="">-</option>
                    @foreach(\App\Person::where('person_type', \App\Person::PERSON_TYPE_DEALER)->get() as $dealer)
                    <option value="{{$dealer->id}}" {{$dealer->id == request()->dealer ? 'selected' : ''}}>{{$dealer->name}} ({{$dealer->person_type}}) - [ID: {{$dealer->system_id}}]</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Select Block</label>
                <select class="form-control" name="block">
                    <option value="">-</option>
                    @foreach(\App\Block::all() as $block)
                    <option value="{{$block->id}}" {{$block->id == request()->block ? 'selected' : ''}}>{{$block->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Select Size</label>
                <select class="form-control" name="property_marla">
                    <option value="">-</option>
                    @foreach(\App\PropertyMarla::all() as $property_marla)
                    <option value="{{$property_marla->marlas}}" {{$property_marla->marlas == request()->property_marla ? 'selected' : ''}}>{{$property_marla->marlas}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Number</label>
                <input type="text" class="form-control" name="property_number" value="{{request()->property_number}}">
            </div>

            <div class="form-group">
                <label>File Number</label>
                <input type="text" class="form-control" name="file_number" value="{{request()->file_number}}">
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
                    <th>Dealer</th>
                    <th>File Number</th>
                    <th>Block</th>
                    <th>Plot Size</th>
                    <th>Plot Number</th>
                    <th>Collection (Receipts)</th>
                    <th>Rebate</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report_data as $dealer_file)
                <tr>
                    <td class="text-center">{{$dealer_file->current_dealer != null ? $dealer_file->current_dealer->name : ''}}</td>
                    <td class="text-center">{{$dealer_file->file_number}}</td>
                    <td class="text-center">{{ $dealer_file->block != null ? $dealer_file->block->name : ''}}</td>
                    <td class="text-center">{{$dealer_file->marlas}}</td>
                    <td class="text-center">{{$dealer_file->property_number}}</td>
                    <td class="text-center">{{$dealer_file->total_installment_receipts}}</td>
                    <td class="text-center">{{$dealer_file->booking != null ? $dealer_file->booking->dealer_commission_amount : '-'}}</td>
                    <td class="text-center">{{$dealer_file->getOpenOrOtherStatus()}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>