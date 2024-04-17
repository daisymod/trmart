@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.register_merch')</div>
            <div class="card-body">
                <div class="form-ajax">
                    <form>
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach
                        <div class="mb-3 row">
                            <div class="col-md-4">
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
