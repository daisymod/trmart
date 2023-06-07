@extends("body")
@section("title", "Тарифы")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                @lang('system.m15')
                @can("shipping-method-list")
                    <a class="btn btn-sm btn-link" href="{{ route("shipping_method.list") }}">{{ __("menu.shipping_method.list") }}</a>
                @endcan
                <div class="menu">@include("menu")</div>
            </div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.name')</th>
                        <th>@lang('system.m15')</th>
                        <th>@lang('system.m16')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }}</td>
                            <td>{{ $i->price }}</td>
                            <td>{{ $i->period }}</td>
                            <td class="controls">
                                @can("shipping-rate-edit", $i)
                                    <a href="{{ route("shipping_rate.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("shipping-rate-del", $i)
                                    <a data-question="Удалить {{ $i->name }}?" href="{{ route("shipping_rate.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("slider-add")
                    <a href="{{ route("shipping_rate.add", $id) }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
