@extends("body")
@section("title", "Курс валюты")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.currency_rate')<div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <form class="form-main-box">
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach
                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <a href="{{ route("currency_rate.list", 0) }}" class="btn btn-back btn-block" >@lang('system.cancel')</a>
                            </div>
                            <div class="col-md-8">
                                <button class="btn btn-primary btn-block">
                                    @lang('system.save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
