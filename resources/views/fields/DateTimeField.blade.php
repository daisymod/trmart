@if ($action == "find")
    <div class="col-md-2">
        <label class="col-form-label">{{ __($class->name) }}</label>
        <input name="{{ $class->field }}" type="datetime-local" class="form-control" value="{{ $value }}">
    </div>
@else
    <div class="form-group form-field-box-{{ $class->field }}">
        <div class="form-label">{{ __($class->name) }}</div>
        <input name="{{ $class->field }}" type="datetime-local" class="form-control" value="{{ $value }}">
        <div class="error-field-text error-{{ $class->field }}"></div>
    </div>
@endif
