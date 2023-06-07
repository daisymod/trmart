@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m11')<div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <div class="merchant-data-form-wrap form-ajax customer__profile">
                        <form class="main-form customer">
                            <div class="left">
                                <h5>@lang('system.m5')</h5>
                                <div class="inputs-wrap">
                                    <span>@lang('system.lk12')</span>
                                    {!! $form["name"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('system.lk11')</span>
                                    {!! $form["s_name"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('system.lk13')</span>
                                    {!! $form["m_name"] !!}
                                </div>
                                <div class="inputs-group">
                                    <div class="inputs-wrap">
                                        <span>@lang('system.m49')</span>
                                        {!! $form["birthday"] !!}
                                    </div>
                                    <div class="inputs-wrap">
                                        <span>@lang('system.m50')</span>
                                        {!! $form["gender"] !!}
                                    </div>
                                    <div class="inputs-wrap">
                                        {!! $form["role"] !!}
                                    </div>
                                    <div class="inputs-wrap">
                                        {!! $form["active"] !!}
                                    </div>
                                </div>
                                <button class="green-btn" type="submit">@lang('system.save')</button>
                            </div>
                            <div class="middle">
                                <h5>@lang('system.m51')</h5>
                                <div class="inputs-wrap">
                                    <span>@lang('system.lk10')</span>
                                    {!! $form["phone"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('system.m32')</span>
                                    {!! $form["email"] !!}
                                </div>
                                <h5>@lang('cart.addressDelivery')</h5>
                                <select class="form-control" name="country_id" id="profile-country_id">
                                    <option selected disabled>Выберите страну</option>
                                    @foreach($countries as $item)
                                        <option @if(intval($record->country_id) === $item->id) selected @endif value="{{$item->id}}">{{$item->name_ru}}</option>
                                    @endforeach
                                </select>
                                <select class="form-control" name="region_id" id="profile-region_id">
                                    <option disabled selected>Выберите область</option>
                                    @if($regions)
                                        @foreach($regions as $item)
                                            <option @if(intval($record->region_id) === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <select class="form-control" name="area_id" id="profile-area_id">
                                    <option disabled selected>Выберите район</option>
                                    @if($areas)
                                        @foreach($areas as $item)
                                            <option @if(intval($record->area_id) === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <select class="form-control" name="city_id" id="profile-city_id">
                                    <option disabled selected>Выберите город</option>
                                    @if($cities)
                                        @foreach($cities as $item)
                                            <option @if(intval($record->city_id) === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <select class="form-control" name="postcode_id" id="profile-postcode">
                                    @if($postCodes)
                                        @foreach($postCodes as $item)
                                            <option @if(intval($record->postcode_id) === $item->id) selected @endif value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    @else
                                        <option disabled selected id="profile-postcode__def">Выберите Индекс</option>
                                    @endif
                                </select>
                                <div class="inputs-wrap">
                                    {!! $form["address_invoice"] !!}
                                </div>
                                <div style="display: flex;justify-content: space-between" class="inputs-wrap">
                                    <div style="width: 49%">{!! $form["house_number"] !!}</div>
                                    <div style="width: 49%">{!! $form["room"] !!}</div>
                                </div>
                                <div style="display: none; visibility: hidden">
                                    <input type="hidden" name="country_title" id="profile-country_title" value="{{$record->country_title}}">
                                    <input type="hidden" name="city_title" id="profile-city_title" value="{{$record->city_title}}">
                                    <input type="hidden" name="region_title" id="profile-region_title" value="{{$record->region_title}}">
                                    <input type="hidden" name="area_title" id="profile-area_title" value="{{$record->area_title}}">
                                    <input type="hidden" name="postcode" id="profile-postcode_id" value="{{$record->postcode_id}}">
                                </div>
                                <button class="green-btn" type="submit">@lang('system.takeChange')</button>
                            </div>
                            <div class="right">
                                <h5> @lang('system.m53')</h5>
                                <div class="inputs-wrap">
                                    <span>Telegram:</span>
                                    {!! $form["telegram"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>Whatsapp:</span>
                                    {!! $form["whatsapp"] !!}
                                </div>
                                <button class="green-btn" type="submit">@lang('system.save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
