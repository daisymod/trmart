@extends("body")
@section("title", "Личный кабинет")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("merchant_top")
            <div class="personal-area-wrap">
                <div class="general-data">
                    <p>@lang('system.m')</p>
                    <div class="general-data-wrap">
                        <div class="top">
                            <div class="total-revenues">
                                <span class="text">@lang('system.m1')</span>
                                <p class="green">{{ $statisticAll }} <span>₺l</span></p>
                            </div>
                            <div class="last-mount">
                                <div class="mount-select">
                                    <select name="" id="">
                                        <option value="1">@lang('system.m2')</option>
                                        <option value="2">@lang('system.m3')</option>
                                    </select>
                                    <p>{{ $statisticMonth }} <span>₺l</span></p>
                                </div>
                            </div>
                            <div class="total-goods">
                                <span class="text">@lang('system.m4')</span>
                                <p>{{ $countProduct ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="personal-data">
                            <div class="texts">
                                <p>{{ Auth::user()->name }}</p>
                                <a href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>
                            </div>
                            <a href="{{ route("merchant.self") }}" class="green-btn">@lang('system.m5')</a>
                        </div>
                        <p>@lang('system.m6')  {{ Auth::user()->created_at }}</p>
                    </div>
                </div>

            </div>
            <div class="info-items">
                <a href="{{route('catalog_item.add')}}" class="info-item">
                    <img src="/img/icon1.svg" alt="">
                    <span>@lang('cabinet.add')</span>
                </a>
                <a href="{{route('merchant.orders')}}" class="info-item">
                    <img src="/img/icon2.svg" alt="">
                    <span>@lang('cabinet.MyOrder')</span>
                </a>
                <a href="{{route('merchant.orders')}}" class="info-item">
                    <img src="/img/icon3.svg" alt="">
                    <span>@lang('cabinet.AddPay')</span>
                </a>
            </div>
        </div>
    </div>
@endsection
