@extends("body")
@section("title", "Страницы")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">{{ $record->name }}</div>
            <div class="card-body">
                {!! str_replace("\n", "<br>", $record->body) !!}
            </div>
        </div>
    </div>
@endsection
