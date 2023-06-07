@extends("body")
@section("title", "Администраторы")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.admin')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.fio')</th>
                        <th>@lang('system.phone')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }}</td>
                            <td>{{ $i->phone }}</td>

                            <td class="controls">
                                @can("admin-edit", $i)
                                    <a href="{{ route("admin.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
