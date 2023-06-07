@extends("body")
@section("title", "Parser")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header"><div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div>
                    <form class="form-main-box" method="post" action="{{route('parseOzdilekteyim')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="locale" value="{{app()->getLocale()}}">
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach

                        <div class="form-group" style="width: 100%">
                            <button class="btn btn-primary btn-block">
                                @lang('system.save')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
