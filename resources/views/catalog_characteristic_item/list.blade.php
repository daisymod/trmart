@extends("body")
@section("title", "Возможные значения")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.random') @endlang <a href="{{ route("catalog_characteristic.list") }}">Характеристики каталога</a>
                <div class="menu">@include("menu")</div>
            </div>
            <div class="card-body table-scroll-wrap">

                <table class="table table-striped table-hover table-bordered table-dnd">
                    <thead>
                    <tr>
                        <th width="25px"></th>
                        <th>№</th>
                        <th>@lang('system.name')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td class="dragRow" data-id="{{ $i->id }}">
                                <i class="fas fa-arrows-alt-v"></i>
                            </td>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->lang("name") }}</td>
                            <td class="controls">
                                @can("catalog-characteristic-item-edit", $i)
                                    <a href="{{ route("catalog_characteristic_item.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("catalog-characteristic-item-del", $i)
                                    <a data-question="Удалить {{ $i->name }}?" href="{{ route("catalog_characteristic_item.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("catalog-characteristic-item-add")
                    <a href="{{ route("catalog_characteristic_item.add", ["characteristic" => $parent]) }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
