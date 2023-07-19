@extends("body")
@section("title", "Главная")
@section("content")
    <div class="personal-area market-personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('admin.cabinet') <span>@lang('admin.marketplace') </span></p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div class="info-items info-items4">
                <a href="#" class="info-item">
                    <img src="/img/icon2.svg" alt="">
                    <span>@lang('admin.active-cart')</span>
                </a>
                <a href="#" class="info-item">
                    <img src="/img/icon3.svg" alt="">
                    <span>@lang('admin.table-sell')</span>
                </a>
                <a href="#" class="info-item">
                    <img src="/img/icon1.svg" alt="">
                    <span></span>
                </a>
                <a href="#" class="info-item">
                    <img src="/img/icon4.svg" alt="">
                    <span>@lang('admin.sliders')</span>
                </a>
            </div>
            <div class="dashboard-items">
                <div class="dashboard-item">
                    <p>@lang('admin.stats-merchant') <a href="#">@lang('admin.all-merchant')</a></p>
                    <div class="dashboard-info">
                        <div class="texts">
                            <div class="info">
                                <b>@lang('admin.total-merchant')</b>
                                <span class="total-merchant">{{$totalMerchant}}</span>
                            </div>
                            <div class="info">
                                <div class="drop-wrap">
                                    <b class="arrow">@lang('admin.last-month')</b>
                                    <ul>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                    </ul>
                                </div>
                                <span class="green">+ {{$totalMerchantMonth}}</span>
                            </div>
                            <div class="info">
                                <b>@lang('admin.change')</b>
                                <span class="red">{{number_format($diffPercentMerchant, 2, '.', ' ')}} %</span>
                            </div>
                        </div>
                        <img src="/img/dashboard-img1.png" alt="">
                    </div>
                </div>
                <div class="dashboard-item">
                    <p>@lang('admin.stats-buyers') <a href="#">@lang('admin.all-buyers')</a></p>
                    <div class="dashboard-info">
                        <div class="texts">
                            <div class="info">
                                <b>@lang('admin.total-buyers')</b>
                                <span class="total-users">{{$totalUser}}</span>
                            </div>
                            <div class="info">
                                <div class="drop-wrap">
                                    <b class="arrow">@lang('admin.last-month')</b>
                                    <ul>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                    </ul>
                                </div>
                                <span class="green">+{{$totalUserMonth}}</span>
                            </div>
                            <div class="info">
                                <b>@lang('admin.change')</b>
                                <span class="green">{{number_format($diffPercentUser, 2, '.', ' ')}} %</span>
                            </div>
                        </div>
                        <img src="/img/dashboard-img2.png" alt="">
                    </div>
                </div>
                <div class="dashboard-item">
                    <p>@lang('admin.stats-products') <a href="#">@lang('admin.lsit-products')</a> <a href="#">@lang('admin.storage')</a></p>
                    <div class="dashboard-info">
                        <div class="texts">
                            <div class="info">
                                <b>@lang('admin.stats-sell')</b>
                                <span class="total-price-stat">{{$totalPrice}} </span>
                            </div>
                            <div class="info">
                                <div class="drop-wrap">
                                    <b class="arrow">@lang('admin.last-month')</b>
                                    <ul>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                    </ul>
                                </div>
                                <span class="green">+ {{$totalPriceMonth}}</span>
                            </div>
                            <div class="info">
                                <b>@lang('admin.change')</b>
                                <span class="green">{{number_format($diffPercentPrice, 2, '.', ' ')}} %</span>
                            </div>
                        </div>
                        <img src="/img/dashboard-img3.png" alt="">
                    </div>
                </div>
                <div class="dashboard-item">
                    <p>@lang('admin.stats-sell')<a href="#">@lang('admin.sells')</a></p>
                    <div class="dashboard-info">
                        <div class="dashboard-select">
                            <span>@lang('admin.choice-level')</span>
                            <div class="select-wrap dark">
                                <select name="" id="">
                                    <option value="1">@lang('admin.choice-1')</option>
                                    <option value="2">@lang('admin.choice-2')</option>
                                    <option value="3">@lang('admin.choice-3')</option>
                                </select>
                            </div>
                        </div>
                        <div class="texts">
                            <div class="info">
                                <b>@lang('admin.total-merchant')</b>
                                <span class="total-merchant">{{ $totalMerchant }}</span>
                            </div>
                            <div class="info">
                                <div class="drop-wrap">
                                    <b class="arrow">@lang('admin.last-month')</b>
                                    <ul>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                        <li><a href="#">@lang('admin.last-year')</a></li>
                                    </ul>
                                </div>
                                <span class="green">+ {{ $totalMerchantMonth }}</span>
                            </div>
                            <div class="info">
                                <b>@lang('admin.change')</b>
                                <span class="red">{{ number_format($diffPercentMerchant, 2, '.', ' ')}}%</span>
                            </div>
                        </div>
                        <a href="#" class="green-btn">@lang('admin.edit-commission')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
