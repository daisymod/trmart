@extends("body")
@section("title", "Регистрация")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-3">
                        <h1 class="page-title">Подтверждение по СМС</h1>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-no-ajax">
                            <form method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Код</label>
                                    <input name="sms" type="text" required class="form-control" value="{{ old("sms") }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Регистрация</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
