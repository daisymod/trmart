@extends("fields.RelationField_table")
@section("table")
    <table class="table table-striped table-hover table-bordered">
        <thead>
        <tr>
            <th></th>
            <th>№</th>
            <th>@lang('system.name')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $k=>$i)
            <tr>
                <td>
                    <input type="checkbox" class="relation-checkbox" data-id="{{ $i->id }}" data-name="{{ $i->name }}" @if(in_array($i->id, array_keys($config["selectId"] ?? []))) checked @endif>
                </td>
                <td>{{ $i->id }}</td>
                <td>{{ $i->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
