@if ($action == "find")
    <div class="col-md-2">
        <label class="col-form-label">{{ __($class->name) }} От</label>
        <input oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" name="{{ $class->field }}[start]" type="number" class="form-control" value="{{ $value["start"] ?? "" }}">
    </div>
    <div class="col-md-2">
        <label class="col-form-label">{{ __($class->name) }} До</label>
        <input oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" name="{{ $class->field }}[end]" type="number" class="form-control" value="{{ $value["end"] ?? "" }}">
    </div>
@else
    <div class="form-group form-field-box-{{ $class->field }}">
        <div class="form-label">{{ __($class->name) }}</div>
            <input oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" name="{{ $class->field }}" type="number" step="1" class="form-control" value="{{ $value }}">
        <div class="error-field-text error-{{ $class->field }}"></div>
    </div>
@endif
