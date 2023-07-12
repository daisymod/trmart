@extends("body")
@section("title", " TurkiyeMart— интернет-магазин модной одежды, обуви и аксессуаров")
@section("content")
    @if(count($slider) > 0)
        <div class="home-slider-wrap">
        <div class="slider-navigation">
            <button type="button" class="slick-next"><img src="/img/arrow-right.svg" alt=""></button>
            <button type="button" class="slick-prev"><img src="/img/arrow-left.svg" alt=""></button>
        </div>
        <div class="home-slider">
            @foreach($slider as $item)
                <a href="{{$item->link}}">
                    <div class="item" style="background-image: url({{$item->main_img}})">
                        @if(!empty($item->name))
                            <div class="item-wrap">
                                <div class="persent">
                                    <p><span class="text">{{$item->name}}</span></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="top-products-slider-wrap">
        <div class="wrapper">
            <div class="title-wrap">
                <h2 class="main-title">@lang('index.top30')<a href="#">@lang('index.all')</a></h2>
                <div class="slider-navigation">
                    <button type="button" class="slick-next"><img src="/img/arrow-right-small.svg" alt=""></button>
                    <button type="button" class="slick-prev"><img src="/img/arrow-left-small.svg" alt=""></button>
                </div>
            </div>
            <div class="top-products-slider">
                @foreach($top as $item)
                 @if($item->main_img != null)
                    <a href="{{ route('shop.item',['id' => $item->id]) }}" class="item">
                    <span class="img-wrap"><img src="{{$item->main_img }}" alt="">
                        @if($item->sale > 0)
                            <b>- {{$item->sale}} %</b>
                        @endif
                    </span>
                    <p>
                        <span class="new price-product" data-price="{{$item->new_price}}"></span>
                        @if($item->sale > 0)
                            <span class="old-price" data-old-price="{{ $item->price }}">{{ $item->price }}</span>
                        @endif
                    </p>
                    <span class="text">{{ $item->{'name_'.app()->getLocale()} }}</span>
                </a>
                 @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="sale-wrap">
        <div class="wrapper">
            <div class="title-wrap">
                <h2 class="main-title">@lang('index.OnlyNew')<a href="#">@lang('index.all')</a></h2>
                <div class="select">
                    <select class="red-select">
                        <option value="1">@lang('index.choice1')</option>
                        <option value="2">@lang('index.choice2')</option>
                        <option value="3">@lang('index.choice3')</option>
                    </select>
                </div>
            </div>
            <div class="sale-items">
                @foreach($newItems as $item)
                    @if(!empty($item->main_img))
                    <a href="{{ route('shop.item',['id' => $item->id]) }}" class="sale-item">
                    <span class="img-wrap"><img src="{{$item->main_img }}" alt="">
                        @if($item->sale > 0)
                            <b>- {{$item->sale}} %</b>
                        @endif
                    </span>
                    <p>
                        <span class="new price-product" data-price="{{$item->new_price}}"></span>
                        @if($item->sale > 0)
                            <span class="old-price" data-old-price="{{ $item->price }}">{{ $item->price }}</span>
                        @endif
                    </p>
                    <span class="text">{{ $item->{'name_'.app()->getLocale()} }}</span>
                </a>
                    @endif
                @endforeach

            </div>
            <a id="show-more-product" class="show-more">@lang('index.ShowAlso')</a>
        </div>
    </div>

    <div class="brands-slider-wrap">
        <div class="wrapper">
            <div class="title-wrap">
                <h2 class="main-title">@lang('index.marksShop') <a href="#">@lang('index.all')</a></h2>
                <div class="slider-navigation">
                    <button type="button" class="slick-next"><img src="/img/arrow-right-small.svg" alt=""></button>
                    <button type="button" class="slick-prev"><img src="/img/arrow-left-small.svg" alt=""></button>
                </div>
            </div>
            <div class="brands-slider">
                <?php $count = 0;  ?>
                @foreach($brands as $brand)
                    @if(($count % 2) != 0)
                            <?php $count ++ ?>
                            <?php continue; ?>
                    @endif

                    <div class="item">
                            <a href="{{$brand->link}}" class="brand-logo"><img src="{{$brand->main_img}}" alt=""></a>
                        @if(isset($brands[$count+1]->link))
                            <a href="{{$brands[$count+1]->link}}" class="brand-logo"><img src="{{$brands[$count+1]->main_img}}" alt=""></a>
                        @else
                            <?php break; ?>
                        @endif
                    </div>

                     <?php $count ++ ?>
                @endforeach
            </div>
        </div>
    </div>

    <div class="style-wrap">
        <p>@lang('index.bottomText')</p>
    </div>
@endsection
