@extends("body")
@section("title", "Избранное")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="customer-panel customer-favorites__wrap">
                <div class="customer-favorites__header">
                    <div class="favorites__header__left">
                        <h5 class="favorites__header_title">@lang('customer.other.your_saved_items')</h5>
                        <div class="favorites__header_count">{{ count($data) }}</div>
                        <a href="{{ route("customer.delAll", \Illuminate\Support\Facades\Auth::user()->id) }}" class="favorites__header_deleted-all">@lang('customer.other.delete_all')</a>
                    </div>
                    <div class="favorites__header__right">
                        <div class="form-group sm">
                            <select name="category-filter" id="category-filter" class="form-control">
                                <option value="all">@lang('customer.other.all_categories')</option>
                                @foreach($categories as $item)
                                <option value="{{$item['id']}}">{{$item['name_'.app()->getLocale()]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--div class="form-group sm">
                            <select name="select1" id="select1" class="form-control">
                                <option value="0">@lang('system.m39')</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div-->
                        <div class="form-group sm">
                            <select name="brand-filter" id="brand-filter" class="form-control">
                                <option value="all">@lang('customer.other.brand')</option>
                                @foreach($brands as $item)
                                    <option value="{{$item['brand']}}">{{$item['brand']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="sale-items" id="sale-items">
                    @foreach($data as $item)
                        <div class="sale-item">
                            <a href="{{ route('shop.item',['id' => $item['catalog_items_id'] ]) }}" class="img-wrap">
                                <img src="{{ asset($item['image'][0]['file']) }}" alt="">
                            </a>
                            <a class="delete-product" href="{{ route("customer.del", $item['id']) }}">&times;</a>
                            <p>
                                <span class="new price-product" data-price="{{$item['price']}}">{{ number_format($item['price'], 0, '', ' ') }} ₺l</span>
                            </p>
                            <span class="text">{{ $item['name_ru'] }}</span>
                            <span class="text">{{ $item['brand'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @include('customer_bottom')
        </div>
    </div>
@endsection
