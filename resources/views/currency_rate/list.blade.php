@extends("body")
@section("title", "Курс валюты $record->name")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.currency_rate') {{ $record->name }} <div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>В какую валюту</th>
                        <th>От/До</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->currency_to->name ?? "" }}</td>
                            <td>{{ $i->rate_start }} / {{ $i->rate_end }}</td>
                            <td class="controls">
                                @can("currency-rate-edit", $i)
                                    <a href="{{ route("currency_rate.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("currency-rate-del", $i)
                                    <a data-question="Удалить {{ $i->name }}?" href="{{ route("currency_rate.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("currency-rate-add")
                    <a href="{{ route("currency_rate.add", $id) }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
