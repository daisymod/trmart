<div class="mb-3 row">
    <label class="col-md-4 col-form-label">{{ __($class->name) }}</label>
    <div class="col-md-8">
        <input name="{{ $class->field }}" class="form-control" value="" placeholder="">
        <div class="error-field-text error-{{ $class->field }}"></div>
    </div>
</div>


