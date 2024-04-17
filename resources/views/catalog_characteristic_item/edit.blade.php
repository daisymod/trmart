@extends("body")
@section("title", "Значение")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.sense') <div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <form action="{{route('translate_catalog_characteristic_item.gpt',['id' => $record->id])}}" method="post">
                        @csrf
                        <input type="submit" class="btn btn-primary btn-block" value="@lang('item.gpt')">
                    </form>


                    <form class="form-main-box">
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach
                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <a href="javascript: history.go(-1)" class="btn btn-back btn-block" > @lang('system.cancel') </a>
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
