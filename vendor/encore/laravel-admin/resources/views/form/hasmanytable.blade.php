<style>
    td .form-group {
        margin-bottom: 0 !important;
    }

    .table-has-many td:not(.remove-td) {
        min-width: 250px;
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary" style="border: 1px solid lightgray;">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $label }}</h3>
            </div>
            <div class="box-body">
                <div id="has-many-{{$column}}" style="margin-top: 15px;" class="table-responsive">
                    <table class="table table-bordered table-has-many has-many-{{$column}}">
                        <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach

                            <th class="hidden"></th>

                            @if($options['allowDelete'])
                                <th></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody class="has-many-{{$column}}-forms">
                        @foreach($forms as $pk => $form)
                            <tr class="has-many-{{$column}}-form fields-group">

                                <?php $hidden = ''; ?>

                                @foreach($form->fields() as $field)

                                    @if (is_a($field, \Encore\Admin\Form\Field\Hidden::class))
                                        <?php $hidden .= $field->render(); ?>
                                        @continue
                                    @endif

                                    <td>{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                                @endforeach

                                <td class="hidden">{!! $hidden !!}</td>

                                @if($options['allowDelete'])
                                    <td class="form-group">
                                        <div>
                                            <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <template class="{{$column}}-tpl">
                        <tr class="has-many-{{$column}}-form fields-group">

                            {!! $template !!}

                            <td class="form-group remove-td">
                                <div>
                                    <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                                </div>
                            </td>
                        </tr>
                    </template>

                    @if($options['allowCreate'])
                        <div class="text-right">
                            <div class="add btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;{{ trans('Add') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<hr style="margin-top: 0px;">

