@extends("body")
@section("title", "Комиссии Категорий")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.commission1')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <div class="row">
                    <label for="name">
                        @lang('system.mySearch')
                        <input style="border: 1px solid #000000; border-radius: 4px;" id="search-fields" name="search" type="text">
                    </label>

                </div>


                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>@lang('system.category')</th>
                        <th>@lang('system.percent_c')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->{"name_".app()->getLocale()} }}</td>
                            <td>{{ $i->commission }}</td>

                            <td class="controls">
                                @can("commission-edit", $i)
                                    <a href="{{ route("commission.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                @endcan
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
