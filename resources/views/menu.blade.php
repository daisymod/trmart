<li @if(Route::currentRouteName() == "user.lk")class="active"@endif>
    <a href="{{ route("user.lk") }}">
        @if(Route::currentRouteName() == "user.lk")
            <img src="/img/menu-icon.svg" alt="">
        @endif
            @lang('menu.index')
    </a>
</li>

@can("merchant-self")
    <li @if(Route::currentRouteName() == "merchant.self")class="active"@endif>
        <a href="{{ route("merchant.self") }}">
            @if(Route::currentRouteName() == "merchant.self")
                <img src="/img/menu-icon2.svg" alt="">
            @endif
                @lang('menu.profile')
        </a>
    </li>
@endcan

@can("customer-self")
    <li @if(Route::currentRouteName() == "customer.self")class="active"@endif>
        <a href="{{ route("customer.self") }}">
            @if(Route::currentRouteName() == "customer.self")
                <img src="/img/menu-icon2.svg" alt="">
            @endif
                @lang('menu.myData')
        </a>
    </li>
@endcan

@can("customer-favorites")
    <li @if(Route::currentRouteName() == "customer.favorites")class="active"@endif>
        <a href="{{ route("customer.favorites") }}">
            @if(Route::currentRouteName() == "customer.favorites")
                <img src="/img/menu-icon12.svg" alt="">
            @endif
                @lang('menu.favorite')
        </a>
    </li>
@endcan

@can("customer-orders")
    <li @if(Route::currentRouteName() == "customer.orders" || Route::currentRouteName() == "customer.order")class="active"@endif>
        <a href="{{ route("customer.orders") }}">
            @if(Route::currentRouteName() == "customer.orders" || Route::currentRouteName() == "customer.order")
                <img src="/img/menu-icon7.svg" alt="">
            @endif
                @lang('menu.buy')
        </a>
    </li>
@endcan

@can("logist-acceptance")
    <li @if(Route::currentRouteName() == "logist.acceptance" || Route::currentRouteName() == "logist.acceptance")class="active"@endif>
        <a href="{{ route("logist.acceptance") }}">
            @if(Route::currentRouteName() == "logist.acceptance" || Route::currentRouteName() == "logist.acceptance")
                <img src="/img/menu-icon7.svg" alt="">
            @endif
            @lang('menu.acceptance')
        </a>
    </li>
@endcan

@can("logist-orders")
    <li @if(Route::currentRouteName() == "logist.orders" || Route::currentRouteName() == "logist.order")class="active"@endif>
        <a href="{{ route("logist.orders") }}">
            @if(Route::currentRouteName() == "logist.orders" || Route::currentRouteName() == "logist.order")
                <img src="/img/menu-icon7.svg" alt="">
            @endif
            @lang('menu.orders_stock')
        </a>
    </li>
@endcan

@can("logist-collected")
    <li @if(Route::currentRouteName() == "logist.collected" || Route::currentRouteName() == "logist.collected.item")class="active"@endif>
        <a href="{{ route("logist.collected") }}">
            @if(Route::currentRouteName() == "logist.collected" || Route::currentRouteName() == "logist.collected.item")
                <img src="/img/menu-icon3.svg" alt="">
            @endif
            @lang('menu.f103')
        </a>
    </li>
@endcan



@can("logist-collected-archival")
    <li @if(Route::currentRouteName() == "logist.archival.collected" || Route::currentRouteName() == "logist.archival.collected.item")class="active"@endif>
        <a href="{{ route("logist.archival.collected") }}">
            @if(Route::currentRouteName() == "logist.archival.collected" || Route::currentRouteName() == "logist.archival.collected.item")
                <img src="/img/menu-icon3.svg" alt="">
            @endif
            @lang('menu.f103_archival')
        </a>
    </li>
@endcan


@can("logist-collected")
    <li @if(Route::currentRouteName() == "logist.all_orders")class="active"@endif>
        <a href="{{ route("logist.all_orders") }}">
            @if(Route::currentRouteName() == "logist.all_orders" )
                <img src="/img/menu-icon3.svg" alt="">
            @endif
            @lang('customer.orders.status.status_all')
        </a>
    </li>


    <li @if(Route::currentRouteName() == "logist.auto_orders")class="active"@endif>
        <a href="{{ route("logist.auto_orders") }}">
            @if(Route::currentRouteName() == "logist.auto_orders" )
                <img src="/img/menu-icon3.svg" alt="">
            @endif
            @lang('customer.orders.auto_orders')
        </a>
    </li>
@endcan

@if(\Illuminate\Support\Facades\Auth::user()->role == 'admin' || ((\Illuminate\Support\Facades\Auth::user()->role == 'merchant') && (Auth::user()->status == 2)))
    <li @if(Route::currentRouteName() == "catalog_item.list")class="active"@endif>
        <a href="{{ route("catalog_item.list") }}">
            @if(Route::currentRouteName() == "catalog_item.list")
                <img src="/img/menu-icon3.svg" alt="">
            @endif{{ __("menu.catalog_item.list") }}
        </a>
    </li>
@endif

@can("country-list")
    <li @if(Route::currentRouteName() == "geo.list")class="active"@endif>
        <a href="{{ route("geo.list") }}">
            @if(Route::currentRouteName() == "geo.list")
                <img src="/img/menu-icon3.svg" alt="">
            @endif{{ __("menu.geo.list") }}
        </a>
    </li>
@endcan

@if( (Auth::user()->role == 'merchant' && Auth::user()->status == 2) || (Auth::user()->role == 'admin'))
<li @if(Route::currentRouteName() == "merchant.orders")class="active"@endif>
    <a href="{{ route("merchant.orders") }}">
        @if(Route::currentRouteName() == "merchant.orders")
            <img src="/img/menu-icon12.svg" alt="">
        @endif
        @lang('menu.orders')
    </a>
</li>
@endif


@if( Auth::user()->role == 'merchant' && Auth::user()->status == 2)
    <li @if(Route::currentRouteName() == "merchant.getPays")class="active"@endif>
        <a href="{{ route("merchant.getPays") }}">
            @if(Route::currentRouteName() == "merchant.getPays")
                <img src="/img/menu-icon12.svg" alt="">
            @endif
            @lang('menu.pays')
        </a>
    </li>
@endif


@if( Auth::user()->role == 'admin')
    <li @if(Route::currentRouteName() == "catalog_characteristic.list")class="active"@endif>
        <a href="{{ route("catalog_characteristic.list") }}">
            @if(Route::currentRouteName() == "catalog_characteristic.list")
                <img src="/img/menu-icon12.svg" alt="">
            @endif
            @lang('system.characteristic')
        </a>
    </li>
@endif

@if( Auth::user()->role == 'admin')
    <li @if(Route::currentRouteName() == "compound.list")class="active"@endif>
        <a href="{{ route("compound.list") }}">
            @if(Route::currentRouteName() == "compound.list")
                <img src="/img/menu-icon12.svg" alt="">
            @endif
            @lang('catalog_item.form.compound')
        </a>
    </li>
@endif


@if(\Illuminate\Support\Facades\Auth::user()->role == 'admin' || ((\Illuminate\Support\Facades\Auth::user()->role == 'merchant') && (Auth::user()->status == 2)))
    <li @if(Route::currentRouteName() == "catalog_item.stock.list")class="active"@endif>
        <a href="{{ route("catalog_item.stock.list") }}">
            @if(Route::currentRouteName() == "catalog_item.stock.list")
                <img src="/img/menu-icon8.svg" alt="">
            @endif{{ __("menu.catalog_item.stock") }}
        </a>
    </li>
@endif


@if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
    <li @if(Route::currentRouteName() == "catalog.list")class="active"@endif>
        <a href="{{ route("catalog.list", 0) }}">
            @if(Route::currentRouteName() == "catalog.list")
                <img src="/img/menu-icon8.svg" alt="">
            @endif{{ __("menu.catalog.list") }}
        </a>
    </li>
@endif

@can("commission-list")
    <li @if(Route::currentRouteName() == "commission.list")class="active"@endif>
        <a href="{{ route("commission.list", 0) }}">
            @if(Route::currentRouteName() == "commission.list")
                <img src="/img/menu-icon8.svg" alt="">
            @endif{{ __("menu.commission.list") }}
        </a>
    </li>
@endcan

@can("user-list")
    <li @if(Route::currentRouteName() == "user.list")class="active"@endif>
        <a class="nav-link" href="{{ route("user.list") }}">{{ __("menu.user.list") }}</a>
    </li>
@endcan

@can("brand-list")
    <li @if(Route::currentRouteName() == "brand.list")class="active"@endif>
        <a class="nav-link" href="{{ route("brand.list") }}">{{ __("menu.brand.list") }}</a>
    </li>
@endcan


@if(\Illuminate\Support\Facades\Auth::user()->role == 'admin' || ((\Illuminate\Support\Facades\Auth::user()->role == 'merchant') && (Auth::user()->status == 2)))
    <li @if(Route::currentRouteName() == "userCart.list")class="active"@endif>
        <a class="nav-link" href="{{ route("userCart.list") }}">{{ __("menu.userCart.list") }}</a>
    </li>
@endif

@can("merchant-list")
    <li @if(Route::currentRouteName() == "merchant.list")class="active"@endif>
        <a class="nav-link" href="{{ route("merchant.list") }}">{{ __("menu.merchant.list") }}</a>
    </li>
@endcan
@can("admin-list")
    <li @if(Route::currentRouteName() == "admin.list")class="active"@endif>
        <a class="nav-link" href="{{ route("admin.list") }}">{{ __("menu.admin.list") }}</a>
    </li>
@endcan

@can("slider-list")
    <li @if(Route::currentRouteName() == "slider.list")class="active"@endif>
        <a class="nav-link" href="{{ route("slider.list") }}">{{ __("menu.slider.list") }}</a>
    </li>
@endcan


@can("currency-list")
    <li @if(Route::currentRouteName() == "currency.list")class="active"@endif>
        <a class="nav-link" href="{{ route("currency.list") }}">{{ __("menu.currency.list") }}</a>
    </li>
@endcan

@if(Auth::user()->role == 'admin')
    <li @if(Route::currentRouteName() == "parser.list")class="active"@endif>
        <a class="nav-link" href="{{ route("parser.list") }}">{{ __("menu.parser.list") }}</a>
    </li>
@endif


@if(Auth::user()->role == 'admin')
    <li @if(Route::currentRouteName() == "ParseStatistic.list")class="active"@endif>
        <a class="nav-link" href="{{ route("ParseStatistic.list") }}">{{ __("menu.parser.list") }} - Statistic </a>
    </li>
@endif

@if(Auth::user()->role == 'admin')
    <li @if(Route::currentRouteName() == "ParserImport.list")class="active"@endif>
        <a class="nav-link" href="{{ route("ParserImport.list") }}">{{ __("menu.parser.list") }} - Statistic / Import </a>
    </li>
@endif


@can("delivery-index")
    <li @if(Route::currentRouteName() == "delivery.index")class="active"@endif>
        <a class="nav-link" href="{{ route("delivery.index") }}">{{ __("menu.delivery.index") }}</a>
    </li>
@endcan


@can("delivery-index")
    <li @if(Route::currentRouteName() == "auto.index")class="active"@endif>
        <a class="nav-link" href="{{ route("auto.index") }}">{{ __("menu.auto.index") }}</a>
    </li>
@endcan
