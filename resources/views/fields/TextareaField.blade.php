@if ($action == "find")
    <div class="col-md-2">
        <label class="col-form-label">{{ __($class->name) }}</label>
        <input @if($readonly == true) readonly @endif name="{{ $class->field }}" type="text" class="form-control" value="{{ $value }}">
    </div>
@else
    <div class="form-group form-group-big form-field-box-{{ $class->field }}">
        <div class="form-label">
            {{ __($class->name) }}<span class="desc">{{ $class->desc ?? "" }}</span>
        </div>
        <textarea @if($readonly == true) readonly @endif name="{{ $class->field }}" class="form-control">{{ $value }}</textarea>
        <div class="error-field-text error-{{ $class->field }}"></div>
    </div>
@endif
