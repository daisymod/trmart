@extends("fields.LanguageBasicField")
@section("content")
    @foreach($langList as $k=>$i)
        <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
            <textarea name="{{ $class->field }}[{{ $k }}]" class="form-control">{{ $value[$k] }}</textarea>
        </div>
    @endforeach
@endsection
