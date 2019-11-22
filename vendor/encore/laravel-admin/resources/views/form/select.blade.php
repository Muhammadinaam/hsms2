<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

<label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="hidden" name="{{$name}}"/>

        @if(isset($show_add_button) && $show_add_button == true)
        <div class="input-group">
        @endif
            <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
                @if($groups)
                    @foreach($groups as $group)
                        <optgroup label="{{ $group['label'] }}">
                            @foreach($group['options'] as $select => $option)
                                <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    <option value=""></option>
                    @foreach($options as $select => $option)
                        <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
                    @endforeach
                @endif
            </select>
            @if(isset($show_add_button) && $show_add_button == true)    
            <span class="input-group-btn">
                <button class="btn btn-primary" type="button">
                    <i class="fa fa-plus-circle"></i>
                </button>
            </span>
            @endif
        @if(isset($show_add_button) && $show_add_button == true)
        </div>
        @endif

        @include('admin::form.help-block')

    </div>
</div>
