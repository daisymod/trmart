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
                    <input type="hidden" id="currency_id" name="currency_id" value="1" >
                    <div class="filters-wrap">
                        <a href="#" onclick="history.go(-1)" class="reset-filters-btn">@lang('system.back')</a>
                        <a href="{{ url()->current() }}" class="reset-filters-btn">@lang('system.clear_filter')</a>
                        <div class="filter-drop-wrap">
                            <h3>@lang('system.i5')   &nbsp; &nbsp;  <p id="currency_symbol"></p>  <img src="/img/arr-right.svg" alt="" class="rotate"></h3>
                            <div class="hide-wrap open">
                                <div class="inputs">
                                    <input type="text" name="price_from" value="{{$_GET['price_from'] ?? ''}}" placeholder="0">
                                    <input type="text" name="price_to"  value="{{$_GET['price_to'] ?? ''}}" placeholder="3 450">
                                </div>
                            </div>
                        </div>
                        <div class="filter-drop-wrap">
                            <h3>@lang('system.m40') </h3>
                            <div class="hide-wrap-2 open">
                                @foreach($uniqueBrand as $brand)
                                    <label class="label-checkbox-text-2" for="brand[{{$brand->id}}]">
                                        {{$brand->brand }}
                                        @if(isset($_GET['brand'][$brand->id] ) && (in_array($brand->brand,$_GET['brand'])) )
                                            <input  checked style="margin-right:10px;height: 15px!important; cursor: pointer"  class="small-checkbox" type="checkbox" name="brand[{{$brand->id}}]" value="{{$brand->brand}}">
                                        @else
                                            <input  class="small-checkbox" style="margin-right:10px; height: 15px!important;cursor: pointer"    type="checkbox" name="brand[{{$brand->id}}]" value="{{$brand->brand}}">
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="filter-drop-wrap">
                            <h3>@lang('catalog_item.form.compound') </h3>
                            <div class="hide-wrap-2 open">
                                @foreach($uniqueCompound as $item)
                                    <label class="label-checkbox-text-2" for="compound[{{$item->id}}]">
                                        {{$item->{'name_'.app()->getLocale()} }}
                                        @if(isset($_GET['compound'][$item->id] ) && (in_array($item->{'name_'.app()->getLocale()},$_GET['compound'])) )
                                            <input  checked style="height: 15px!important;margin-right:10px; cursor: pointer"  class="small-checkbox" type="checkbox" name="compound[{{$item->id}}]" value="{{ $item->{'name_'.app()->getLocale()} }}">
                                        @else
                                            <input  class="small-checkbox" style="height: 15px!important;margin-right:10px;cursor: pointer"    type="checkbox" name="compound[{{$item->id}}]" value="{{ $item->{'name_'.app()->getLocale()} }}">
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="filter-drop-wrap">
                            <h3>@lang('system.color')   </h3>
                            <div class="hide-wrap-2 open">
                                @foreach($uniqueColor as $color)
                                    <label class="label-checkbox-text-2" for="color[{{$color->color}}]">
                                        @if(empty($color->colorData->{'name_'.app()->getLocale()}))
                                            <p></p>
                                        @else
                                            {{$color->colorData->{'name_'.app()->getLocale()} }}
                                        @endif

                                        @if(isset($_GET['color'][$color->color] ) && (in_array($color->color,$_GET['color'])) )
                                            <input  checked style="margin-right:10px;height: 15px!important; cursor: pointer"  class="small-checkbox" type="checkbox" name="color[{{$color->color}}]" value="{{$color->color}}">
                                        @else
                                            <input  class="small-checkbox" style="margin-right:10px;height: 15px!important;cursor: pointer"    type="checkbox" name="color[{{$color->color}}]" value="{{$color->color}}">
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>


                        <div class="filter-drop-wrap">
                            <h3>@lang('system.size')   </h3>
                            <div class="hide-wrap-2 open">
                                @foreach($uniqueSize as $size)
                                    <label class="label-checkbox-text-2" for="size[{{$size->size}}]">
                                        @if(empty($size->sizeData->{'name_'.app()->getLocale()}))
                                            <p></p>
                                        @else
                                            {{$size->sizeData->{'name_'.app()->getLocale()} }}
                                        @endif

                                        @if(isset($_GET['size'][$size->size]) && (in_array($size->size,$_GET['size'])) )
                                            <input  checked style="margin-right:10px;height: 15px!important; cursor: pointer"  class="small-checkbox" type="checkbox" name="size[{{$size->size}}]" value="{{$size->size}}">
                                        @else
                                            <input  class="small-checkbox" style="margin-right:10px;height: 15px!important;cursor: pointer"    type="checkbox" name="size[{{$size->size}}]" value="{{$size->size}}">
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @foreach($filter as $item)
                            <div class="filter-drop-wrap">
                                <h3>{{$item->{'name_'.app()->getLocale()} }}</h3>
                                <div class="hide-wrap-2 open">
                                    @php $arrayUnique = array(); @endphp
                                    @foreach($item->dynamicCharacteristic as $option)
                                        @if(!in_array($option->{'name_'.app()->getLocale()},$arrayUnique))
                                            <label class="label-checkbox-text-2" for="item[{{$item->id}}]">
                                                {{$option->{'name_'.app()->getLocale()} }}
                                                @if(isset($_GET['item'][$item->id] ) && (in_array($option->{'name_'.app()->getLocale()},$_GET['item'])) )
                                                    <input  checked style="margin-right:10px;height: 15px!important; cursor: pointer"  class="small-checkbox" type="checkbox" name="item[{{$item->id}}]" value="{{$option->{'name_'.app()->getLocale()} }}">
                                                @else
                                                    <input  class="small-checkbox" style="margin-right:10px;height: 15px!important;cursor: pointer"    type="checkbox" name="item[{{ $item->id}}]" value="{{$option->{'name_'.app()->getLocale()} }}">
                                                @endif
                                            </label>
                                            @php array_push($arrayUnique,$option->{'name_'.app()->getLocale()}) @endphp
                                        @endif

                                    @endforeach
                                </div>
                            </div>
                        @endforeach


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
                                        <span class="img-wrap"><img src="{{ $i->image() }}" alt="">
                                         @if($item->sale > 0)
                                                <b>- {{$item->sale}} %</b>
                                            @endif
                                        </span>
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
    <div class="Pager2">
        {{ $items->withQueryString()->links( "pagination::bootstrap-4") }}
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
