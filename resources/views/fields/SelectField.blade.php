@if ($action == "find")
    <div class="form-group">
        <label class="col-form-label">{{ __($class->name) }}</label>
        <select name="{{ $class->field }}" class="form-control">
            <option value=""></option>
            @foreach ($class->data as $k=>$i)
                <option value="{{ $k }}" @if((string)$k === (string)$value) selected @endif >{{ $i }}</option>
            @endforeach
        </select>
    </div>
@else
    <div class="form-group form-field-box-{{ $class->field }}">
        <div class="form-label">{{ __($class->name) }}</div>
        <select class="form-control" name="{{ $class->field }}">
            @foreach ($class->data as $k=>$i)
                <option value="{{ $k }}" @if((string)$k === (string)$value) selected @endif >{{ $i }}</option>
            @endforeach
        </select>
    </div>
@endif
