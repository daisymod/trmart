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
        </div>
    </div>
@endsection
