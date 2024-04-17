@extends("body")
@section("title", "Страницы")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m18') <div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.m13') </th>
                        <th>@lang('system.m19') </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }}</td>
                            <td><a href="{{ route("page.url", $i->url) }}">{{ route("page.url", $i->url) }}</a></td>
                            <td class="controls">
                                @can("page-edit", $i)
                                    <a href="{{ route("page.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("page-del", $i)
                                    <a data-question="Удалить {{ $i->name }}?" href="{{ route("page.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("page-add")
                    <a href="{{ route("page.add") }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
