@extends("body")
@section("title", "Compound")
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
                        <th>Название</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->{'name_'.app()->getLocale()} }}</td>

                            <td class="controls">
                                <a href="/compound/{{$i->id }}"><i class="fas fa-edit"></i></a>
                                <a data-question="Удалить {{ $i->{'name_'.app()->getLocale()} }}?" href="/compound/{{$i->id }}/delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
