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
                        <th>Время выполнения / 1 запись</th>
                        <th>Статус</th>
                        <th>Файл результат</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>
                                @if($i->user->role == 'admin')
                                    {{ $i->user->name. ' ' .$i->user->s_name }}
                                @else
                                    {{ $i->user->company->company_name}}
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($i->start_parse) }}</td>
                            <td>{{ \Carbon\Carbon::parse($i->end_parse) }}</td>
                            <td>{{ $i->count_of_lines }}</td>
                            @php
                                $date = \Carbon\Carbon::parse($i->start_parse);
                                $end = \Carbon\Carbon::parse($i->end_parse);
                                $diff = $date->diff($end);
                                $diff_time = $diff->h .':' .$diff->i.':' .$diff->s;
                            @endphp
                            <td>{{$diff_time}}</td>

                            @php
                                sscanf($diff_time, "%d:%d:%d", $hours, $minutes, $seconds);
                                $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                                $time_one = $time_seconds / $i->count_of_lines;
                            @endphp
                            <td>{{number_format($time_one, 2, '.', ' ')}}</td>
                            <td>
                            @if($i->end_parse == null)
                                    in progress
                            @else
                                    done
                            @endif
                            </td>
                            <td class="controls">
                            <a href="/files/{{$i->file}}" class="btn gren-btn">Файл результата</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
