@extends("body")
@section("title", "Каталог")
@section("content")
    <div class="search-result-wrap">
        <div class="wrapper">
            <ul class="breadcrumbs-list">
                <li><a href="/">@lang('menu.index')</a></li>
                @foreach($breadcrumbs as $k=>$i)
                    <li><span><a href="{{ route("shop.list", $i->id) }}">{{ $i->{'name_'.app()->getLocale()} }}</a></span></li>
                @endforeach
            </ul>
            <div class="top">
                <h2>{{ $record->name }}<span> {{ $count }} товар</span></h2>

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
                    @foreach($filter as $item)
                            <div class="filter-drop-wrap">
                                <h3>{{$item->items->{'name_'.app()->getLocale()} }}<img src="/img/arr-right.svg" alt="" class="rotate"></h3>
                                <div>
                                    <div>
                                        @foreach($item->items->items as $option)
                                            <label class="label-checkbox-text" for="item[{{$item->items->id}}]">
                                                {{$option->{'name_'.app()->getLocale()} }}
                                                @if(isset($_GET['item'][$item->items->id] ) && (in_array($option->id,$_GET['item'])) )
                                                    <input  checked  class="small-checkbox" type="checkbox" name="item[{{$item->items->id}}]" value="{{$option->id}}">
                                                @else
                                                    <input  class="small-checkbox" type="checkbox" name="item[{{$item->items->id}}]" value="{{$option->id}}">
                                                @endif
                                            </label>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                    @endforeach



                    <input type="submit"  class="btn green-btn" value="@lang('menu.search')">

                </div>
                </form>
                <div class="search-result-tab-wrap">

                    <div class="tab-content2">
                        <div class="tab-content-item active">
                            <div class="sale-items">
                                @foreach($items as $k=>$i)
                                    @if(!empty($i->image()))
                                        <a href="{{ route("shop.item", $i->id) }}" class="sale-item">
                                            <span class="img-wrap"><img src="{{ $i->image() }}" alt=""></span>
                                            {{--<p>
                                                <span class="new price-product" data-price="1000"">1 500 ₺l</span>
                                                <span class="old">1 830 ₺l</span>
                                            </p>--}}
                                            <p>
                                                <span class="new price-product" data-price="{{ number_format($i->price, 0, ".", " ") }}">{{ number_format($i->price, 0, ".", " ") }} ₺l</span>
                                            </p>
                                            <span class="text">{{ $i->lang("name") }}</span>
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
