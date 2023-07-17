@extends("body")
@section("title", "Parse stat")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Пользоватлеь</th>
                        <th>Дата начала</th>
                        <th>Дата конца</th>
                        <th>к-во записей в файле</th>
                        <th>Время выполнения</th>
                        <th>Файл результат</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->user->name. ' ' .$i->user->s_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($i->start_parse) }}</td>
                            <td>{{ \Carbon\Carbon::parse($i->end_parse) }}</td>
                            <td>{{ $i->count_of_lines }}</td>
                            <td>{{ \Carbon\Carbon::parse($i->start_parse - $i->end_parse)->format('H:i:s') }}</td>
                            <td class="controls">
                            <a href="{{$i->file}}" class="btn gren-btn">Файл результата</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
