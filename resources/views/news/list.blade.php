@extends("body")
@section("title", "Новости")
@section("content")

    {{--
        <div class="container">
            <div class="card">
                <div class="card-header">Новости<div class="menu">@include("menu")</div></div>
                <div class="card-body table-scroll-wrap">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Название</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $k=>$i)
                            <tr>
                                <td>{{ $i->id }}</td>
                                <td>{{ date("d.m.Y", strtotime($i->dt)) }}</td>
                                <td>{{ $i->name }}</td>
                                <td>{{ $i->category }}</td>

                                <td class="controls">
                                    @can("news-edit", $i)
                                        <a href="{{ route("news.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                        &nbsp;&nbsp;&nbsp;
                                    @endcan
                                    @can("news-del", $i)
                                        <a data-question="Удалить {{ $i->name }}?" href="{{ route("news.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @can("news-add")
                        <a href="{{ route("news.add") }}" class="btn btn-primary">Добавить</a>
                        &nbsp;&nbsp;&nbsp;
                    @endcan
                </div>
            </div>
        </div>--}}
    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('system.news')</p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div>
                <div class="top">
                    <p>@lang('system.news_from')</p>
                    <div class="right-info">
                        <div class="select-wrap dark">
                            <select name="" id="">
                                <option value="1">@lang('system.i6')  20</option>
                                <option value="2">@lang('system.i6') 30 </option>
                                <option value="3">@lang('system.i6') 40 </option>
                            </select>
                        </div>
                    </div>
                </div>
                @foreach($records as $k=>$i)
                    <div>
                        <div class="left">
                            <span class="text active">{{ $i->name }}</span>
                            <p>
                                <span class="date">{{ date("d.m.Y", strtotime($i->dt)) }}</span>
                                <span class="green">{{ $i->category }}</span>
                            </p>
                        </div>
                        <p>
                            {{ $i->body }}
                            @can("news-edit", $i)
                                &nbsp;&nbsp;&nbsp;
                                <a href="{{ route("news.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                            @endcan
                            @can("news-del", $i)
                                &nbsp;&nbsp;&nbsp;
                                <a data-question="Удалить {{ $i->name }}?" href="{{ route("news.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                            @endcan
                        </p>
                    </div>
                @endforeach
                @can("news-add")
                    <a href="{{ route("news.add") }}" class="green-btn">@lang('system.add')</a>
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
