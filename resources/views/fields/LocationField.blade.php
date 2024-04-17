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
        <div class="form-label">
            {{ __($class->name) }}<span class="desc">{{ $class->desc ?? "" }}</span>
        </div>
        <div class="location-box">
            <div class="control-box text-box">
                <div class="text">
                    @lang('system.m35'): <span class="country-text">{{ $value->country->title }}</span>,
                    @lang('system.m34'): <span class="city-text">{{ $value->city->title }}</span><br>
                    <span class="clear-select">@lang('cart.clear') <i class="fas fa-broom"></i></span>
                </div>
                <input type="hidden" name="country_title" value="{{ $value->country->title }}">
                <input type="hidden" name="city_id" value="{{ $value->city->id }}">
                <input type="hidden" name="city_title" value="{{ $value->city->title }}">
            </div>
            <div class="country-box control-box">
                <div class="col-form-label">@lang('system.m35')</div>
                <select name="country_id" class="form-control country-select">
                    @foreach($countries->items as $k=>$i)
                        <option value="{{ $i->id }}" @if($i->id == $value->country->id) selected @endif>{{ $i->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="city-box control-box">
                <div class="col-form-label">@lang('system.m34')</div>
                <div class="city-load-box">
                    <input value="{{Auth::user()->city_title ?? '' }}" class="form-control city-find" placeholder="@lang('system.f6')">
                    <img class="load-progress" src="/i/6.gif">
                </div>
            </div>
        </div>

    </div>
@endif
