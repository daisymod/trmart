@extends("body")
@section("title", "Товары")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('system.lk') </p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div class="add-new-product-text">
                <a href="javascript:history.back()"><img src="/img/back-icon.png" alt=""></a>
                <span class="text">@lang('system.lk1')</span>
                <span class="green">@lang('system.lk2')</span>
            </div>
            <div class="product-style-wrap">
                <div class="product-style-block">
                    <img src="/img/info-icon.svg" alt="">
                    <p>@lang('system.lk3')</p>
                </div>
                <div class="product-style-block">
                    <img src="/img/info-icon.svg" alt="">
                    <p>@lang('system.lk4')
                        <a href="#">@lang('system.lk5')</a></p>
                </div>
            </div>
            <div class="form-ajax">
                <form class="form-main-box">
                    @foreach($form as $k=>$i)
                        {!! $i !!}
                    @endforeach
                    <div class="form-group form-group-big">
                        <button class="green-btn" type="submit">
                            @lang('system.lk6')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
