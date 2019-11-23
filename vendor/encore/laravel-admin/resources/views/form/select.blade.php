<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

<label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="hidden" name="{{$name}}"/>

        @if(isset($add_button_url) && $add_button_url != '')
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
            @if(isset($add_button_url) && $add_button_url != '')    
            <span class="input-group-btn">
                <button class="btn btn-primary add_new_button" onClick="loadUrlInModal('{{url($add_button_url)}}')" type="button">
                    <i class="fa fa-plus-circle"></i>
                </button>
            </span>
            @endif
        @if(isset($add_button_url) && $add_button_url != '')
        </div>
        @endif

        @include('admin::form.help-block')

    </div>
</div>
