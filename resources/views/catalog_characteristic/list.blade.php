@extends("body")
@section("title", "Характеристики каталога")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.characteristic')
                <div class="menu">@include("menu")</div>
            </div>
            <div class="card-body table-scroll-wrap">

                <table class="table table-striped table-hover table-bordered table-dnd">
                    <thead>
                    <tr>
                        <th width="25px"></th>
                        <th>№</th>
                        <th>@lang('system.name')</th>
                        <th>@lang('system.db_characteristic')</th>
                        <th> @lang('system.basic_characteristic')</th>
                        <th></th>
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
                            <td>
                                @if(in_array($i->field,$columns))
                                    {{ $i->field }}
                                @else
                                    <div class="error">{{ $i->field }}</div>
                                @endif
                            </td>
                            <td>@if($i->basic == "Y")
                                    Да
                                @else
                                    Нет
                                @endif</td>
                            <td>
                                @can("catalog-characteristic-item-list", $i->id)
                                    <a href="{{ route("catalog_characteristic_item.list", $i->id) }}">
                                        @lang('system.random') ({{ ($i->items()->getQuery()->count()) }})
                                    </a>
                                @endcan
                            </td>
                            <td class="controls">
                                @can("catalog-characteristic-edit", $i)
                                    <a href="{{ route("catalog_characteristic.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                @can("catalog-characteristic-del", $i)
                                    @if($i->id != 15 && $i->id != 16)
                                            <a data-question="Удалить {{ $i->name }}?" href="{{ route("catalog_characteristic.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                    @endif

                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can("catalog-characteristic-add")
                    <a href="{{ route("catalog_characteristic.add") }}" class="btn btn-primary">@lang('system.add')</a>
                    &nbsp;&nbsp;&nbsp;
                @endcan
            </div>
        </div>
    </div>
@endsection
