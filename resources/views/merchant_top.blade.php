<div class="top">
    <p>@lang('system.lk')</p>
    <ul class="main-menu">
        @include("menu")
    </ul>
</div>
<div class="info-block">
    <a href="{{ route("merchant.self") }}" class="item @if($merchantStep == 1) active @endif">
        <span class="number">1</span>
        <span class="text">@lang('system.f1')</span>
        <img src="/img/info-img1.png" alt="">
    </a>
    @can("catalog-item-list")
        <a href="{{ route("catalog_item.list", 0) }}" class="item @if($merchantStep == 2) active @endif">
            <span class="number">2</span>
            <span class="text">@lang('system.f2')</span>
            <img src="/img/info-img2.png" alt="">
        </a>
    @endcan
    @can("catalog-item-stock-list")
        <a href="{{ route("catalog_item.stock.list") }}" class="item @if($merchantStep == 3) active @endif">
            <span class="number">3</span>
            <span class="text">@lang('system.f3')</span>
            <img src="/img/info-img3.png" alt="">
        </a>
    @endcan
    <a href="#" class="item @if($merchantStep == 4) active @endif">
        <span class="number">4</span>
        <span class="text">@lang('system.f4')</span>
        <img src="/img/info-img4.png" alt="">
    </a>
</div>
