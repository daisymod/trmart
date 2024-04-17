@extends("body")
@section("title", "Валюты")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.currency')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.name')</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }}</td>
                            @can("currency-rate-list")
                                <td>
                                    <a href="{{ route("currency_rate.list", $i->id) }}">
                                        Курс обмена ({{ ($i->rates()->getQuery()->count()) }})
                                    </a>
                                </td>
                            @endcan
                            <td class="controls">
                                @can("currency-edit", $i)
                                    <a href="{{ route("currency.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("currency-add")
                    <a href="{{ route("currency.add") }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
