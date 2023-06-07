@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m20')<div class="menu">@include("menu")</div></div>
            <div class="card-body">
                <div class="form-ajax">
                    <form style="width: 100%;display: flex;flex-wrap: wrap;justify-content: center;"  class="main-form">
                        <div style="display: flex; flex-wrap: wrap; width: 50%">

                            <div style="width: 100%" class="inputs-wrap">

                                {!! $form["first_name"] !!}
                            </div>
                            <div style="width: 100%" class="inputs-wrap">

                                {!! $form["last_name"] !!}
                            </div>
                            <div style="width: 100%" class="inputs-wrap">

                                {!! $form["patronymic"] !!}
                            </div>
                            <div style="width: 100%" class="inputs-wrap">

                                {!! $form["phone"] !!}


                            </div>

                            <div style="width: 100%" class="inputs-wrap">
                                {!! $form["email"] !!}
                            </div>
                            <div style="width: 100%" class="inputs-wrap">
                                {!! $form["company_name"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["shop_name"] !!}
                            </div>

                            <div class="inputs-wrap">
                                {!! $form["reg_form"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["tckn"] !!}
                            </div>
                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["vkn"] !!}
                            </div>
                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["iban"] !!}
                            </div>
                            <div style="width: 100%"  class="inputs-wrap">
                                <span>@lang('system.qq12')</span>
                                <select class="form-control" name="area_id">
                                    <option selected disabled>@lang('system.qq13')</option>
                                    @foreach($area as $item)
                                        <option @if($record->area_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                <span>@lang('system.qq5')</span>
                                <select class="form-control" name="city_id" id="merchant-city_id">
                                    <option selected disabled>@lang('system.qq5')</option>
                                    @foreach($trAreas as $item)
                                        <option @if($record->city_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="country_title" value="Турция">
                                <input type="hidden" id="merchant-city_title" name="city_title" value="">
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">

                                {!! $form["tax_office"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">

                                {!! $form["type_invoice"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                <span>
                                    @lang('merchant.form.address_invoice')
                                </span>
                                <div class="w-100 mt-2">
                                    <span>@lang('merchant.form.legal_address_city')</span>
                                    <select class="form-control" name="legal_address_city" id="merchant-legal_address_city">
                                        <option selected disabled>@lang('merchant.form.legal_address_city')</option>
                                        @foreach($trAreas as $item)
                                            <option @if($legalAddressCity === $item->name) selected @endif value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="w-100 mt-2">
                                    {!! $form["legal_address_street"] !!}
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="w-50">

                                        {!! $form["legal_address_number"] !!}
                                    </div>
                                    <div class="w-50">

                                        {!! $form["legal_address_office"] !!}
                                    </div>
                                </div>
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">

                                <span>
                                    @lang('merchant.form.address_return')
                                </span>
                                <div class="w-100 mt-2">
                                    <span>@lang('merchant.form.legal_address_city')</span>
                                    <select class="form-control" name="city" id="merchant-city">
                                        <option selected disabled>@lang('merchant.form.city')</option>
                                        @foreach($trAreas as $item)
                                            <option @if($addressCity === $item->name) selected @endif value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="w-100 mt-2">

                                    {!! $form["street"] !!}
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="w-50">

                                        {!! $form["number"] !!}
                                    </div>
                                    <div class="w-50">

                                        {!! $form["office"] !!}
                                    </div>
                                </div>



                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["sale_category"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["active"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["status"] !!}
                            </div>

                            <div style="width: 100%"  class="inputs-wrap">
                                {!! $form["reason"] !!}
                            </div>

                            <div style="width: 100%; display: flex">
                                <div style="width: 49%">
                                    <a href="{{ route("merchant.list") }}" class="btn btn-back btn-block">@lang('system.cancel')</a>
                                </div>
                                <div style="width: 49%">
                                    <button class="btn btn-primary btn-block">
                                        @lang('system.save')
                                    </button>
                                </div>
                            </div>
                        </div>





                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
