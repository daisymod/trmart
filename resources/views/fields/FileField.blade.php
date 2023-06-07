@if ($action == "find")
    <div class="form-group">
        <label class="col-form-label">{{$class->name}}</label>
        <input name="{{$class->field}}" type="text" class="form-control @if ($class->errors) is-invalid @endif" value="{{$value}}">
        <div class="invalid-feedback invalid-feedback-{{$class->field}}">
            @foreach($class->errors as $k=>$i)
                {{$i}}<br>
            @endforeach
        </div>
    </div>
@else
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{$class->name}}</label>
        <div class="col-md-8">
            @if (!empty($value))
                Загружен файл: <a href="{{ Storage::url($value["path"]) }}" target="_blank">{{ $value["name"] }}</a><br>
                Заменить файл:
            @endif
            <input name="{{$class->field}}" type="file" class="form-control @if ($class->errors) is-invalid @endif">
            <div class="invalid-feedback invalid-feedback-{{$class->field}}">
                @foreach($class->errors as $k=>$i)
                    {{$i}}<br>
                @endforeach
            </div>
        </div>
    </div>
@endif
