@if ($action == "find")
    <div class="col-md-4">
        <div class="location-box">
            <div class="city-box control-box">
                <div class="city-load-box">
                    <input class="form-control city-find" placeholder="@lang('system.qq5')">
                    <img class="load-progress" src="/i/6.gif">
                </div>
            </div>
        </div>
    </div>
@else
    <div class="form-group form-group-big form-field-box-{{ $class->field }}">

        <div class="location-box">
            <div class="control-box text-box">
                <input type="hidden" name="country_title" value="{{ $value->country->title }}">
                <input type="hidden" name="city_id" value="{{ $value->city->id }}">
                <input type="hidden" name="city_title" value="{{ $value->city->title }}">
            </div>
            <div class="city-box control-box w-100" style="width: 100%">
                <div class="city-load-box">
                    <input value="{{Auth::user()->city_title ?? '' }}" class="form-control city-find" placeholder="@lang('system.qq5')">
                    <img class="load-progress" src="/i/6.gif">
                </div>
            </div>
        </div>

    </div>
@endif
