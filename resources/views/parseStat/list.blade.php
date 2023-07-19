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

                                <select name="user_id">
                                    <option value="1">Admin</option>
                                    @foreach($users as $user)
                                        @if(isset($user->user->id))
                                            <option value="{{$user->user->id ?? null}}">{{$user->company_name}} / {{$user->user->name.' '.$user->user->s_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
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
                            <td>
                                @if($i->end_parse != null)
                                    {{ \Carbon\Carbon::parse($i->end_parse) }}
                                @endif
                            </td>
                            <td>{{ $i->count_of_lines }}</td>
                            @php
                                $date = \Carbon\Carbon::parse($i->start_parse);
                                $end = \Carbon\Carbon::parse($i->end_parse);
                                $diff = $date->diff($end);
                                $diff_time = $diff->h .':' .$diff->i.':' .$diff->s;
                            @endphp
                            <td>
                                @if($i->end_parse != null)
                                    {{$diff_time}}
                                @endif
                            </td>

                            @php
                                sscanf($diff_time, "%d:%d:%d", $hours, $minutes, $seconds);
                                $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                                $time_one = $time_seconds / $i->count_of_lines;
                            @endphp
                            <td>
                                @if($i->end_parse != null)
                                    {{number_format($time_one, 2, '.', ' ')}}
                                @endif
                            </td>
                            <td>
                            @if($i->end_parse == null)
                                    in progress
                            @else
                                    done
                            @endif
                            </td>
                            <td class="controls">
                                @if($i->file != null)
                                    @if($i->id < 49)
                                        <a href="/files/{{$i->file}}" class="btn gren-btn">Файл результата</a>
                                    @else
                                        <a href="/storage/files/{{$i->file}}" class="btn gren-btn">Файл результата</a>
                                    @endif
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
