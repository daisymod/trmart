@extends("body")
@section("title", "Parse stat")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <div class="customer-orders__filter">
                    <h4>Фильтр</h4>
                    <div class="filter-column">
                        <form method="GET" action="{{ route('ParseStatistic.list') }}">
                            <div class="filter-group">
                                <input type="date" name="start" value="">
                                <input type="date" name="end" value="">
                                <button class="filter-group__btn" type="submit">Применить</button>
                                <a class="filter-group__btn-red" href="{{ route('ParseStatistic.list') }}">Очистить</a>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th width="5%">№</th>
                        <th width="5%">Время вполнения</th>
                        <th width="10%">Сайт</th>
                        <th width="10%">Ссылка парсера</th>
                        <th width="10%">Мерчант</th>
                        <th width="10%">Каталог</th>
                        <th width="10%">к-во записей в файле</th>
                        <th width="5%">Статус</th>
                        <th width="30%">Ошибка</th>
                        <th width="5%">Файл результат</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{gmdate("H:i:s", $i->time)}}</td>
                            <td>{{ $i->domain }}</td>
                            <td>{{ $i->url }}</td>
                            <td>{{ $i->user->name ?? ''  }} {{ $i->user->s_name ?? ''  }} </td>
                            <td>{{ $i->catalogs->{'name_'.app()->getLocale()} ?? ''  }}</td>
                            <td>{{ $i->url }}</td>
                            <td>
                                {{ $i->totalCount }}
                            </td>
                            <td>{{ $i->status }}</td>
                            <td width="30%">{{ $i->error }}</td>
                            <td class="controls">
                                @if($i->file != null)
                                    <a href="/storage/files/{{$i->file}}" class="btn gren-btn">Файл результата</a>
                                @endif
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
