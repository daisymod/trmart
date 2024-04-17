<div class="form-group form-group-big form-field-box-{{ $class->field }}">
    <div class="form-label">
        {{ __($class->name) }}<span class="desc">{{ $class->desc ?? "" }}</span>
    </div>
    <div class="field-images-box {{ $class->class ?? "" }}" data-style="{{ $class->style ?? "" }}" data-width="{{ $class->width ?? 250 }}" data-height="{{$class->height ?? 250}}" data-filed="{{ $class->field }}">
        <div class="images-list-box">
            <div class="image-box image-load" style="display: none">
                <div class="image">
                    <img src="/i/6.gif" style="width: 70px;">
                </div>
                <div class="name">Загрузка изображения</div>
            </div>
            @foreach($value as $info)
                @include("fields.image_box_load")
            @endforeach
        </div>
        <div class="select-file btn btn-primary btn-sm">@lang('system.f5')</div>
    </div>
    <div class="error-field-text error-{{ $class->field }}"></div>
</div>

