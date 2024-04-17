@if ($action == "find")
    <div class="col-md-4">
        <div class="location-box">
            <div class="country-box control-box">
                <div class="col-form-label">@lang('system.m35')</div>
                <select name="country_id" class="form-control country-select">
                    <option value=""></option>
                    @foreach($countries->items as $k=>$i)
                        <option value="{{ $i->id }}" @if($i->id == $value->country->id) selected @endif>{{ $i->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="city-box control-box">
                <div class="col-form-label">@lang('system.m34')</div>
                <div class="city-load-box">
                    <input class="form-control city-find" placeholder="@lang('system.f6')">
                    <img class="load-progress" src="/i/6.gif">
                </div>
            </div>
        </div>
    </div>
@else
    <div class="form-group form-group-big form-field-box-{{ $class->field }}">

        <div class="location-box">
            <div class="country-box control-box">
                <div class="col-form-label">@lang('system.m35')</div>
                <select name="country_id" class="form-control country-select">
                    @foreach($countries->items as $k=>$i)
                        <option value="{{ $i->id }}" @if($i->id == $value->country->id) selected @endif>{{ $i->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
@endif
