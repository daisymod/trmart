@extends("body")
@section("title", "Регистрация")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-3">
                        <h1 class="page-title">@lang('menu.user.reg')</h1>
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
                                    <label class="form-label">@lang('body.name')</label>
                                    <input name="name" type="text" required class="form-control" value="{{ old("name") }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@lang('body.phone')</label>
                                    <input name="phone" type="tel" required class="form-control" value="{{ old("phone") }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@lang('body.password')</label>
                                    <input name="password" type="password" required class="form-control" value="{{ old("password") }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@lang('body.passwordRepeat')</label>
                                    <input name="password_confirmation" type="password" required class="form-control" value="{{ old("password_confirmation") }}">
                                </div>
                                <button type="submit" class="btn btn-primary">@lang('menu.user.reg')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
