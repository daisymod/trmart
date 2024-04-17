@if ($action == "find")
    <div class="form-group">
        <label class="col-form-label">{{ __($class->name) }}</label>

        <input  name="{{ $class->field }}" type="{{ $class->type }}" class="form-control"  value="{{ $value }}">
    </div>
@else
    <div class="form-group form-field-box-{{ $class->field }}">
        <div class="form-label">{{ __($class->name) }}</div>


        <input @if($readonly == true) readonly @endif value="{{ $value }}"
               class="form-control" list="{{ $class->field }}"  name="{{ $class->field }}" type="{{ $class->type }}" @if($class->type == "number") step="{{ $class->step }}" @endif>
        <div class="error-field-text error-{{ $class->field }}"></div>
        <datalist id="{{ $class->field }}">
            @foreach($class->datalist as $k=>$i)
                <option>{{$i}}</option>
            @endforeach
        </datalist>
    </div>
@endif
