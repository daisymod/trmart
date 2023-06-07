@extends("body")
@section("title", "Слайдер")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m12')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.m13')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }}</td>
                            <td class="controls">
                                @can("slider-edit", $i)
                                    <a href="{{ route("slider.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("slider-del", $i)
                                    <a data-question="Удалить {{ $i->name }}?" href="{{ route("slider.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("slider-add")
                    <a href="{{ route("slider.add") }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
