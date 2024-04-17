@if ($action == "find")
    <div class="col-md-4">
        <div class="location-box">
            <div class="city-box control-box">
                <div class="city-load-box">
                    <input class="form-control city-find" placeholder="@lang('system.qq6')">
                    <img class="load-progress" src="/i/6.gif">
                </div>
            </div>
        </div>
    </div>
@else
    <div class="form-group form-group-big form-field-box-{{ $class->field }}">

        <div class="location-box">
            <div class="city-box control-box w-100" style="width: 100%">
                <div class="city-load-box">
                    <input value="{{$company->legal_address_city ?? '' }}" name="legal_address_city" class="form-control city-find" placeholder="@lang('system.qq6')">
                    <img class="load-progress" src="/i/6.gif">
                </div>
            </div>
        </div>

    </div>
@endif
