@extends("fields.RelationField_table")
@section("table")
    <div class="breadcrumb relation-breadcrumb">
        <div class="breadcrumb-item relation-set-config" data-parent-id="0">
            Корень
        </div>
        @foreach($breadcrumbs as $k=>$i)
            <div class="breadcrumb-item relation-set-config" data-parent-id="{{ $i->id }}">
                {{ $i->{'name_'.app()->getLocale()} }}
            </div>
        @endforeach
    </div>

    <table class="table table-striped table-hover table-bordered">
        <thead>
        <tr>
            <th></th>
            <th>№</th>
            <th>@lang('system.name')</th>
            @if(!$config["level1Only"])
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($records as $k=>$i)
            <tr>
                <td>
                    @if((count($i->child) == 0 AND $i->parent_id <> 0) OR $config["selectParent"])
                        <input type="checkbox" class="relation-checkbox" data-id="{{ $i->id }}" data-name="{{ $i->{'name_'.app()->getLocale()} }}" @if(in_array($i->id, array_keys($config["selectId"] ?? []))) checked @endif>
                    @endif
                </td>
                <td>{{ $i->id }}</td>
                <td>{{ $i->{'name_'.app()->getLocale()} }}</td>
                <td>
                    @if(!$config["level1Only"] AND count($i->child) > 0)
                        <a class="relation-set-a relation-set-config" data-parent-id="{{ $i->id }}">@lang('system.i1')({{ count($i->child) }})</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
