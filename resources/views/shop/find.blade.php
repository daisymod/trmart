@extends("body")
@section("title", "Каталог")
@section("content")
    <div class="search-result-wrap">
        <div class="wrapper">
            <ul class="breadcrumbs-list">
                <li><a href="/">@lang('menu.index')</a></li>
                <li><span> @lang('menu.catalog_text')</span></li>
                <li><span>@lang('menu.search')</span></li>
            </ul>
            <div class="top">
                <h2>@lang('system.search1') «{{ $find }}» @lang('system.search2') <span> {{ $count }} @lang('system.search3') </span></h2>

            </div>
            <div class="search-result-info">
                <form >
                    <div class="filters-wrap">
                        <a href="{{ url()->current() }}" class="reset-filters-btn">@lang('system.clear_filter')</a>
                        <div class="filter-drop-wrap">
                            <h3>@lang('system.i5')   <img src="/img/arr-right.svg" alt="" class="rotate"></h3>
                            <div class="hide-wrap open">
                                <div class="inputs">
                                    <input type="text" name="price_from" value="{{$_GET['price_from'] ?? ''}}" placeholder="0">
                                    <input type="text" name="price_to"  value="{{$_GET['price_to'] ?? ''}}" placeholder="3 450">
                                </div>
                            </div>
                        </div>
                        <div class="filter-drop-wrap">
                            <h3>@lang('catalog_item.form.weight') <img src="/img/arr-right.svg" alt="" class="rotate"></h3>
                            <div class="hide-wrap open">
                                <div class="inputs">
                                    <input type="text" name="weight_from" value="{{$_GET['weight'] ?? ''}}" placeholder="0">
                                    <input type="text" name="weight_to"  value="{{$_GET['weight_to'] ?? ''}}" placeholder="3 450">
                                </div>
                            </div>
                        </div>
                        <input type="submit"  class="btn green-btn" value="@lang('menu.search')">

                    </div>
                </form>
                <div class="search-result-tab-wrap">
                    {{--                    <div class="tab-menu-wrap">
                                            <ul class="tab-menu2">
                                                <li><a href="#">Платье</a></li>
                                                <li class="active"><a href="#">Блузка женская</a></li>
                                                <li><a href="#">Брюки женские</a></li>
                                                <li><a href="#">Футболка</a></li>
                                                <li><a href="#">Нижнее белье</a></li>
                                                <li><a href="#">Юбка</a></li>
                                            </ul>
                                        </div>--}}
                    <div class="tab-content2">
                        <div class="tab-content-item active">
                            <div class="sale-items">
                                @foreach($items as $k=>$i)
                                    @if(!empty($i->image()))
                                    <a href="{{ route("shop.item", $i->id) }}" class="sale-item">
                                        <span class="img-wrap"><img src="{{ $i->image() }}" alt=""></span>
                                        <p>
                                            <span class="new price-product"  data-price="{{ number_format($i->new_price, 0, ".", " ") }}">{{ number_format($i->new_price, 0, ".", " ") }} </span>
                                            @if($i->sale > 0)
                                                <span class="old-price" data-old-price="{{ $i->price }}">{{ $i->price }}</span>
                                            @endif
                                        </p>
                                        <span class="text">{{ $i->{'name_'.app()->getLocale()}  }}</span>
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="top-products-slider-wrap detail-top-products">
        <div class="wrapper">
            <div class="title-wrap">
                <h2 class="main-title">@lang('system.YouSee') <a href="#">@lang('system.MaybeFavorite')</a></h2>
                <div class="slider-navigation">
                    <button type="button" class="slick-next"><img src="/img/arrow-right-small.svg" alt=""></button>
                    <button type="button" class="slick-prev"><img src="/img/arrow-left-small.svg" alt=""></button>
                </div>
            </div>
            <div class="top-products-slider">
                @foreach($checkProducts as $item)
                    @if($item->product->image() != null)
                        <a href="{{route('shop.item',['id' => $item->product_id])}}" class="item">
                        <span class="img-wrap"><img src="{{$item->product->image() ?? '#'}}" alt="">
                            @if($item->product->sale > 0)
                                <b>- {{$item->product->sale}} %</b>
                            @endif
                        </span>
                            <p>
                                <span class="new price-product" data-price="{{$item->product->price}}">{{$item->product->price}}</span>
                            </p>
                            <span class="text">{{$item->product->{'name_'.app()->getLocale()} }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="style-wrap">
        <p>@lang('index.bottomText')</p>
    </div>
@endsection
