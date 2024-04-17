@extends("body")
@section("title", "Тарифы")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m14') <div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <form class="form-main-box">
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach
                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <a href="{{ route("shipping_rate.list", $id ?? $record->shipping_method_id) }}" class="btn btn-back btn-block" >@lang('system.cancel')</a>
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
