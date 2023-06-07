@extends("body")
@section("title", "Наличие товара")
@section("content")
    {{--<div class="container">
        <div class="card">
            <div class="card-header">Наличие товара</div>
            <div class="card-body">
                <form class="row mb-3" method="GET">
                    <div class="col-md-2">
                        <label class="col-form-label">Кол-во От</label>
                        <input name="stock_start" type="text" class="form-control" value="{{ request("stock_start", "") }}">
                    </div>
                    <div class="col-md-2">
                        <label class="col-form-label">Кол-во До</label>
                        <input name="stock_end" type="text" class="form-control" value="{{ request("stock_end", "") }}">
                    </div>
                    <div class="col-md-2">
                        <label class="col-form-label">&nbsp;</label>
                        <button class="btn btn-primary btn-block">
                            Применить
                        </button>
                    </div>
                    <div class="col-md-2">
                        <label class="col-form-label">&nbsp;</label>
                        <a href="{{ url()->current() }}" class="btn btn-link btn-block">
                            Сборосить
                        </a>
                    </div>
                </form>

                <form method="POST">
                    {{ csrf_field() }}
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Артикул</th>
                            <th>Название</th>
                            <th>Изображение</th>
                            <th>Цвет</th>
                            <th>Наличие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $k=>$i)
                            <tr>
                                <td>{{ $i->id }}</td>
                                <td>{{ $i->article }}</td>
                                <td>{{ $i->name }}</td>
                                <td>
                                    <img src="{{ $i->image() }}" style="height: 50px;">
                                </td>
                                <td>{{ $i->color }}</td>
                                <td>
                                    <input name="stock[{{ $i->id }}]" class="form-control" value="{{ $i->stock }}" type="number" step="0.01">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @can("catalog-item-stock-save")
                        <button class="btn btn-primary">Сохранить</button>
                    @endcan
                </form>
            </div>
        </div>
    </div>--}}
    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('cabinet.me')</p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div class="merchant-wrap pb25">
                <h4>@lang('cabinet.cartProduct') <span>2 @lang('cabinet.Product')</span></h4>
                <p>@lang('cabinet.newProducts')</p>
                <div class="product-style-wrap">
                    <div class="product-style-block">
                        <img src="/img/info-icon.svg" alt="">
                        <p>@lang('cabinet.withoutPhoto')</p>
                    </div>
                    <div class="product-style-block">
                        <img src="/img/info-icon.svg" alt="">
                        <p>@lang('cabinet.warning')
                            <a href="#">@lang('cabinet.instruction')</a></p>
                    </div>
                </div>

                <form method="GET" action="{{route('catalog_item.stock.list')}}" class="mini-search-form">
                    @foreach($form as $k=>$i)
                        {!! $i !!}
                    @endforeach

                    <div class="form-group">
                        <label class="col-form-label">&nbsp;</label>
                        <button class="btn btn-primary">
                            @lang('system.takeChange')
                        </button>
                    </div>
                </form>

                <div class="table-scroll-wrap">
                    <form method="POST">
                        {{ csrf_field() }}
                        <table cellspacing="1" class="merchant-goods-table">
                            <thead>
                            <tr>
                                <th>@lang('cabinet.article')</th>
                                <th>@lang('cabinet.name')</th>
                                <th>@lang('cabinet.photo')</th>
                                <th>@lang('cabinet.count')</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--<tr>
                                <td><div class="icon-wrap"><img src="/img/check.svg" alt=""></div></td>
                                <td><div class="img-wrap"><img src="/img/table-img.jpg" alt=""></div></td>
                                <td><a href="#" class="name">Джинсы женские клеш зауженные</a></td>
                                <td><span>Levi’s</span></td>
                                <td><span>31626546</span></td>
                                <td><span>Джинсы</span></td>
                                <td><span>316 26546 2465</span></td>
                                <td>
                                    <div class="size-color">
                                        <button>L</button>
                                        <span>Синий</span>
                                    </div>
                                </td>
                                <td><input type="text" class="table-input" placeholder="10"></td>
                                <td><span class="red-text">В процессе...</span></td>
                                <td><span>15.05.2022</span></td>
                                <td>
                                    <div class="buttons">
                                        <a href="#" class="close"><img src="/img/close-icon.svg" alt=""></a>
                                        <a href="#" class="arrow"><img src="/img/arrow-right-small.svg" alt=""></a>
                                    </div>
                                </td>
                            </tr>--}}
                            @foreach($records as $k=>$i)
                                <tr>
                                    <td><span>{{ $i->article }}</span></td>
                                    <td><span><a href="{{route('catalog_item.edit',$i->id)}}">{{ $i->lang("name") }}</a></span></td>
                                    <td>
                                        <div class="img-wrap"><img src="{{ $i->image() }}" style="height: 50px;"></div>
                                    </td>
                                    <td>
                                        <input name="stock[{{ $i->id }}]" class="table-input" value="{{ $i->stock }}" readonly type="text" >
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="Pager2">
                            {{ $records->links( "pagination::bootstrap-4") }}
                        </div>

                        <button type="submit" class="green-btn popup">@lang('system.save')</button>
                    </form>
                </div>
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
