@extends("body")
@section("title", "Пользователи")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">Пользователи<div class="menu">@include("menu")</div></div>
            <form method="GET" action="{{route('user.list')}}" class="mini-search-form">
                @foreach($form as $k=>$i)
                    {!! $i !!}
                @endforeach
                <div class="form-group">
                    <label class="col-form-label">&nbsp;</label>
                    <button class="btn btn-primary">
                        @lang('system.takeChange')
                    </button>
                </div>
                {{--                        <div class="form-group">
                                            <label class="col-form-label">&nbsp;</label>
                                            <a href="{{ url()->current() }}" class="btn btn-link">
                                                Сборосить
                                            </a>
                                        </div>--}}
            </form>

            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>@lang('system.lk9')</th>
                        <th>@lang('system.lk10')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }} {{ $i->s_name }} {{ $i->middle_name }}</td>
                            <td>{{ $i->phone }}</td>
                            <td class="controls">
                                @can("user-edit", $i)
                                    <a href="{{ route("user.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                <!--
                                @can("user-del", $i)
                                    <a href="{{ route("user.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                @endcan -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="Pager2">
                    {{ $records->links( "pagination::bootstrap-4") }}
                </div>
            </div>
        </div>
    </div>
@endsection
