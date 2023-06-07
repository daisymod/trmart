@extends("body")
@section("title", "Color")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <ul class="main-menu">
                    @include("menu")
                </ul>
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
                                <a href="{{ route("color.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                &nbsp;&nbsp;&nbsp;
                            @endcan
                            @can("brand-delete", $i)
                                <a data-question="Удалить {{ $i->name_ru }}?" href="{{ route("color.delete", $i->id) }}"><i class="fas fa-trash"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @can("brand-edit")
                <a href="{{ route("color.create") }}" class="btn btn-primary">@lang('system.add')</a>
                &nbsp;&nbsp;&nbsp;
            @endcan
        </div>
    </div>

@endsection
