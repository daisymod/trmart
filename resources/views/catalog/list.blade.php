@extends("body")
@section("title", "Каталог")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('system.lk')</p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div class="category-tree">
                <h4>@lang('system.lk7')<span>25</span></h4>
                <p>@lang('system.lk8')</p>
                @foreach($records as $k=>$i)
                    <div class="add-item-wrap" style="display: flex">
                        <button class="add-item">
                            {{ $i->{"name_".app()->getLocale()} }}
                            <span class="plus">+</span>
                        </button>
                        <span style="margin-left: 10px" class="edit"><a href="{{ route("catalog.edit", $i->id) }}"><i  class="fa-solid fa-pen"></i></a></span>

                        <div class="add-item-content">
                            @foreach($i->child as $child)
                                <div style="display: flex">
                                    <button class="add-item-child">
                                        {{ $child->{"name_".app()->getLocale()} }}
                                        <span class="plus">+</span>
                                    </button>
                                    <span style="margin-left: 10px" class="edit"><a href="{{ route("catalog.edit", $child->id) }}"><i  class="fa-solid fa-pen"></i></a></span>
                                </div>
                                <div class="add-item-content">
                                    @foreach($child->child as $childSecond)
                                        <div style="display: flex">
                                            <button class="add-item-child">
                                                {{ $childSecond->{"name_".app()->getLocale()} }}
                                                <span class="plus">+</span>
                                            </button>
                                            <span style="margin-left: 10px" class="edit"><a href="{{ route("catalog.edit", $childSecond->id) }}"><i  class="fa-solid fa-pen"></i></a></span>
                                           </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @can("catalog-add")
                    <a href="{{ route("catalog.add", $id) }}" class="green-btn">@lang('system.add')</a>
                @endcan


            </div>
            <div class="info-items">
                <a href="{{route('catalog_item.add')}}" class="info-item">
                    <img src="/img/icon1.svg" alt="">
                    <span>@lang('cabinet.add')</span>
                </a>
                <a href="{{route('merchant.orders')}}" class="info-item">
                    <img src="/img/icon2.svg" alt="">
                    <span>@lang('cabinet.MyOrder')</span>
                </a>
                <a href="{{route('merchant.orders')}}" class="info-item">
                    <img src="/img/icon3.svg" alt="">
                    <span>@lang('cabinet.AddPay')</span>
                </a>
            </div>
        </div>
    </div>

@endsection
