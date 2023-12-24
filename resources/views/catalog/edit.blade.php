@extends("body")
@section("title", "Каталог")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('menu.catalog_text')<div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <form class="form-main-box">
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach

                            <div class="form-group form-field-box-commission">
                                <div class="form-label">@lang('system.delivery_type')</div>


                                <select  class="form-control"  name="type_delivery">
                                    <option @if($record->type_delivery == 1) selected @endif value="1">@lang('system.delivery_type1')</option>
                                    <option @if($record->type_delivery == 2) selected @endif value="2">@lang('system.delivery_type2')</option>
                                </select>

                            </div>

                        @if(isset($active))
                                @if($items > 0)
                                    <div class="product-style-wrap">
                                        <div class="product-style-block"> <img src="/img/info-icon.svg" alt="">
                                            <p>@lang('system.cant_active')</p>
                                        </div>
                                    </div>
                                    <div  class="mb-5 mt-5 ml-2 row" style="display: none">
                                        <label for="is_active">
                                            <input type="checkbox" name="is_active" @if($active == 1) checked @endif>
                                            @lang('system.m22')
                                        </label>
                                    </div>

                                @else
                                    <div style="margin:50px 10px" class="mb-5 mt-5 ml-2 row">
                                        <label for="is_active">
                                            <input type="checkbox" name="is_active" @if($active == 1) checked @endif>
                                            @lang('system.m22')
                                        </label>
                                    </div>
                                @endif
                        @endif


                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <a   href="{{ route("catalog.list", 0) }}" class="btn btn-back btn-block" >@lang('system.cancel')</a>
                            </div>
                            <div class="col-md-8">
                                <button data-question="@lang('system.take_change')" class="btn btn-primary btn-block">
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
