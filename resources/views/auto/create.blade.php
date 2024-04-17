@extends("body")
@section("title", "Auto delivery")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m35')<div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <form action="{{route('auto.store')}}" method="post" class="form-main-box">

                        <div class="mb-3 row">

                            <div class="form-group form-field-box-article">
                                <div class="form-label">@lang('system.from')</div>
                                <input  class="form-control" name="from" type="text">
                            </div>
                            <div class="form-group form-field-box-article">
                                <div class="form-label">@lang('system.to')</div>
                                <input  class="form-control" name="to" type="text">
                            </div>
                            <div class="form-group form-field-box-article">
                                <div class="form-label">@lang('system.price')</div>
                                <input  class="form-control" name="price" type="text">
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route("auto.index") }}" class="btn btn-back btn-block" >@lang('system.cancel')</a>
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
