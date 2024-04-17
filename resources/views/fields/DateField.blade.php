@if ($action == "find")
    <div class="col-md-2">
        <label class="col-form-label">{{ __($class->name) }} От</label>
        <input name="{{ $class->field }}[start]" type="date" class="form-control" value="{{ $value["start"] ?? "" }}">
    </div>
    <div class="col-md-2">
        <label class="col-form-label">{{ __($class->name) }} До</label>
        <input name="{{ $class->field }}[end]" type="date" class="form-control" value="{{ $value["end"] ?? "" }}">
    </div>
@else
    <div class="form-group form-field-box-{{ $class->field }}">
        <div class="form-label">{{ __($class->name) }}</div>
        <input name="{{ $class->field }}" type="date" class="form-control" value="{{ $value }}">
        <div class="error-field-text error-{{ $class->field }}"></div>
    </div>
@endif
