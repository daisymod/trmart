@if ($action == "find")
    <div class="form-group">
        <label class="col-form-label">{{ __($class->name) }}</label>
        <select id="brand-item" name="{{ $class->field }}" class="form-control">
            <option value=""></option>
            @foreach ($data as $k=>$i)
                <option value="{{ $i->name  }}" @if((string)$i->name  === (string)$value->name) selected @endif >{{ $i->name  }}</option>
            @endforeach
        </select>
    </div>
@else
    <div class="form-group form-field-box-{{ $class->field }}">
        <div class="form-label">{{ __($class->name) }}</div>
        <select id="brand-item" class="form-control" name="{{ $class->field }}">
            @foreach ($data as $k=>$i)
                <option value="{{ $i->name}}" @if((string)$i->name  === (string)$value->name) selected @endif >{{ $i->name }}</option>
            @endforeach
        </select>
    </div>
@endif
