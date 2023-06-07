@extends("body")
@section("title", "Способы доставки")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m17')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.m13')</th>
                        @can("shipping-rate-list")
                        <th></th>
                        @endcan
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }}</td>
                            @can("shipping-rate-list")
                                <td>
                                    <a href="{{ route("shipping_rate.list", $i->id) }}">
                                        @lang('system.m14') ({{ ($i->rate()->getQuery()->count()) }})
                                    </a>
                                </td>
                            @endcan
                            <td class="controls">
                                @can("shipping-method-edit", $i)
                                    <a href="{{ route("shipping_method.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("shipping-method-del", $i)
                                    <a data-question="Удалить {{ $i->name }}?" href="{{ route("shipping_method.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("slider-add")
                    <a href="{{ route("shipping_method.add") }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
