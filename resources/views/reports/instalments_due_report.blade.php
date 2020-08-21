<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Instalments Due Report</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    
    <div class="box-body" style="display: block;">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>File Number</th>
                    <th>Holder Name</th>
                    <th>Instalments Due Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report_data as $row)
                    <?php
                        $due_amount = $row->instalments_amount + $row->instalments_receipts_amount;
                    ?>

                    @if($due_amount > 0)
                    <tr>
                        <td>{{$row->file_number}}</td>
                        <td>{{$row->holder_name}}</td>
                        <td>{{$row->instalments_amount + $row->instalments_receipts_amount}}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    </div><!-- /.box-body -->
</div>