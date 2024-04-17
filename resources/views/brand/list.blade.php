@extends("body")
@section("title", "Администраторы")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.admin')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <div>
                    <a href="{{ route("brand.create") }}" class="btn btn-primary">@lang('system.add')</a>
                </div>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.name')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name_ru }}</td>

                            <td class="controls">
                                @can("brand-edit", $i)
                                    <a href="{{ route("brand.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("brand-delete", $i)
                                    <a data-question="Удалить {{ $i->name_ru }}?" href="{{ route("brand.delete", $i->id) }}"><i class="fas fa-trash"></i></a>
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
