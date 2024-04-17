@extends("fields.LanguageBasicField")
@section("content")
    @foreach($langList as $k=>$i)
        <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
            <input class="form-control" list="{{ $class->field }}" name="{{ $class->field }}[{{ $k }}]" type="text" value="{{ $value[$k] }}">
            <datalist id="{{ $class->field }}">
                @foreach($class->datalist as $k=>$i)
                    <option>{{$i}}</option>
                @endforeach
            </datalist>
        </div>
    @endforeach
@endsection
