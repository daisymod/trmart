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
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <th width="5%">№</th>
                        <th width="5%">Время вполнения</th>
                        <th width="10%">Сайт</th>
                        <th width="10%">Ссылка парсера</th>
                        <th width="5%">Мерчант</th>
                        <th width="5%">Каталог</th>
                        <th width="5%">к-во записей в файле</th>
                        <th width="5%">Статус</th>
                        <th width="30%">Ошибка</th>
                        <th width="20%">Файл результат</th>
                    </tr>
                    @foreach($records as $k=>$i)
                        <tr style="height: 200px">
                            <td width="5%">{{ $i->id }}</td>
                            <td width="5%">{{gmdate("H:i:s", $i->time)}}</td>
                            <td width="10%">{{ $i->domain }}</td>
                            <td width="10%">{{ $i->url }}</td>
                            <td width="5%"> {{ $i->user->name ?? ''  }} {{ $i->user->s_name ?? ''  }} </td>
                            <td width="5%"> {{ $i->catalogs->{'name_'.app()->getLocale()} ?? ''  }}</td>
                            <td width="5%">
                                {{ $i->totalCount }}
                            </td >
                            <td width="5%">{{ $i->status }}</td>
                            <td width="30%">{{ $i->error }}</td>
                            <td width="20%">
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

<style>
    td{
        white-space: pre-wrap; /* CSS3 */
        white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /* Internet Explorer 5.5+ */
    }
</style>
