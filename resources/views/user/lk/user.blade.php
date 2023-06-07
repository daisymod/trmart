@extends("body")
@section("title", "Личный кабинет")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="personal-area-wrap">
                <div class="general-data">
                    <p>@lang('system.m')</p>
                    <div class="general-data-wrap">
                        <div class="top">
                            <div class="total-revenues">
                                <span class="text">@lang('system.m10')</span>
                                <p class="green">{{$statisticUserAll}} <span>₸</span></p>
                            </div>
                            <div class="last-mount">
                                <div class="mount-select">
                                    <span class="text">@lang('system.m2')</span>
                                    <p>{{$statisticUserMonth}} <span>₸</span></p>
                                </div>
                            </div>
                            <div class="total-goods">
                                <span class="text">@lang('system.m4')</span>
                                <p>{{$statisticUserCount}}</p>
                            </div>
                        </div>
                        <div class="personal-data">
                            <div class="texts">
                                <p>{{ Auth::user()->name }}</p>
                                <a href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>
                            </div>
                            <a href="{{ route("customer.self") }}" class="green-btn">@lang('system.m5')</a>
                        </div>
                        <p>@lang('system.m6'){{ Auth::user()->created_at }}</p>
                    </div>
                </div>

            </div>
            @include('customer_bottom')
        </div>
    </div>
@endsection
