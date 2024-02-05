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
                        <th>№</th>
                        <th>Время вполнения</th>
                        <th>Сайт</th>
                        <th>Ссылка парсера</th>
                        <th>Мерчант</th>
                        <th>Каталог</th>
                        <th>к-во записей в файле</th>
                        <th>Статус</th>
                        <th width="30%">Ошибка</th>
                        <th>Файл результат</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{gmdate("H:i:s", $i->time)}}</td>
                            <td>{{ $i->domain }}</td>
                            <td>{{ $i->url }}</td>
                            <td>{{ $i->user->first_name ?? ''  }} {{ $i->user->last_name ?? ''  }} </td>
                            <td>{{ $i->catalog->{'name_'.app()->getLocale()} ?? ''  }}</td>
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
