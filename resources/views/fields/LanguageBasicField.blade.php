@if ($action == "find")
    <div class="form-group">
        <label class="col-form-label">{{ __($class->name) }}</label>
        <input name="{{ $class->field }}" type="text" class="form-control" value="{{ $value[$lang] }}">
    </div>
@else
    <div class="form-group @if($class->big == true) form-group-big @endif form-field-box-{{ $class->field }}">
        <div class="form-label form-label-lang">
            <div class="label">{{ __($class->name) }}</div>
            <div class="lang-list">
                @foreach($langList as $k=>$i)
                    <div class="lang @if($k == $lang) active @endif lang-{{ $k }}" data-lang="{{ $k }}">{{ $i["cod"] }}</div>
                @endforeach
            </div>
        </div>
        @yield("content")
        <div class="error-field-text error-{{ $class->field }}"></div>
    </div>
@endif
