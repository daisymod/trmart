@extends("body")
@section("title", "Мои данные")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            @if (!empty(request("send")))
                <div class="alert alert-success">
                    @lang('system.m25')
                </div>
            @endif
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
                        </div>
                    </div>
                    <div class="middle">
                        <h5>@lang('system.m51')</h5>
                        <div class="inputs-wrap">
                            <span>@lang('system.lk10')</span>
                            <input type="text" name="phone" disabled value="{{ Auth::user()->phone }}">
                        </div>
                        <div class="inputs-wrap">
                            <span>@lang('system.m32')</span>
                            {!! $form["email"] !!}
                        </div>
                        <h5>@lang('cart.addressDelivery')</h5>
                            <div class="customer-addresses">
                                <div class="inputs-wrap">
                                    <span>@lang('customer.form-label.fl1')</span>
                                    <select class="form-control" name="country_id" id="profile-country_id">
                                        <option selected disabled>@lang('customer.form-label.fl1')</option>
                                        @foreach($countries as $item)
                                            <option @if(Auth::user()->country_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name_ru}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('customer.form-label.fl2')</span>
                                    <select class="form-control" name="region_id" id="profile-region_id">
                                        <option disabled selected>@lang('customer.form-label.fl2')</option>
                                        @if($regions)
                                            @foreach($regions as $item)
                                                <option @if(Auth::user()->region_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('customer.form-label.fl3')</span>
                                    <select class="form-control" name="area_id" id="profile-area_id">
                                        <option disabled selected>@lang('customer.form-label.fl3')</option>
                                        @if($areas)
                                            @foreach($areas as $item)
                                                <option @if(Auth::user()->area_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('customer.form-label.fl4')</span>
                                    <select class="form-control" name="city_id" id="profile-city_id">
                                        <option disabled selected>@lang('customer.form-label.fl4')</option>
                                        @if($cities)
                                            @foreach($cities as $item)
                                                <option @if(Auth::user()->city_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('customer.form-label.fl5')</span>
                                    <select class="form-control" name="postcode_id" id="profile-postcode">
                                        @if($postCodes)
                                            @foreach($postCodes as $item)
                                                <option @if(Auth::user()->postcode_id === $item->id) selected @endif value="{{$item->id}}">{{$item->title}}</option>
                                            @endforeach
                                        @else
                                            <option disabled selected id="profile-postcode__def">@lang('customer.form-label.fl5')</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        <div class="inputs-wrap">
                            <span>@lang('cart.street')</span>
                            {!! $form["address_invoice"] !!}
                        </div>
                        <div style="display: flex;justify-content: space-between" class="inputs-wrap">
                           <div style="width: 49%">
                               <span style="color: rgba(85,85,85,.6);font-size: 15px;line-height: 20px;margin-bottom: 10px;">@lang('cart.homeNumber')</span>
                               {!! $form["house_number"] !!}
                           </div>
                           <div style="width: 49%;">
                               <span style="color: rgba(85,85,85,.6);font-size: 15px;line-height: 20px;margin-bottom: 10px;">@lang('cart.appartments')</span>
                               {!! $form["room"] !!}</div>
                        </div>
                        <div style="display: none; visibility: hidden">
                            <input type="hidden" name="country_title" id="profile-country_title" value="{{Auth::user()->country_title}}">
                            <input type="hidden" name="city_title" id="profile-city_title" value="{{Auth::user()->city_title}}">
                            <input type="hidden" name="region_title" id="profile-region_title" value="{{Auth::user()->region_title}}">
                            <input type="hidden" name="area_title" id="profile-area_title" value="{{Auth::user()->area_title}}">
                            <input type="hidden" name="postcode" id="profile-postcode_id" value="{{Auth::user()->postcode_id}}">
                        </div>
                        <button @if(isset($_GET['from_cart'])) name="to_cart" @endif class="green-btn" type="submit">@lang('system.takeChange')</button>
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
                    </div>
                </form>
            </div>
            @include('customer_bottom')
        </div>
    </div>
@endsection
