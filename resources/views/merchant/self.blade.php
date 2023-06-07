@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("merchant_top")
            @if (!empty(request("send")))
                <div class="alert alert-success">
                    @lang('system.m25')

                </div>
            @else
                <div class="merchant-data-form-wrap form-ajax">
                    <form class="main-form">
                        <div class="left">
                            <h5>@lang('system.m26')

                                @if(Auth::user()->status == '1')
                                    <a href="#">@lang('system.m27') </a>
                                @endif
                            </h5>
                            <div class="inputs-wrap">
                                <span>@lang('body.companyName')</span>
                                {!! $form["company_name"] !!}
                            </div>

                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.shop_name')</span>
                                {!! $form["shop_name"] !!}
                                <span>@lang('system.qq15')</span>
                            </div>

                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.reg_form')</span>
                                {!! $form["reg_form"] !!}
                            </div>
                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.tckn')</span>
                                {!! $form["tckn"] !!}
                            </div>
                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.vkn')</span>
                                {!! $form["vkn"] !!}
                            </div>
                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.iban')</span>
                                {!! $form["iban"] !!}

                                <span>
                                    @lang('system.qq11')
                                </span>
                            </div>
                            <div class="inputs-wrap">
                                <span>@lang('system.qq12')</span>
                                <select class="form-control" name="area_id">
                                    <option selected disabled>@lang('system.qq13')</option>
                                    @foreach($area as $item)
                                        <option @if(Auth::user()->area_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="inputs-wrap">
                                <span>@lang('system.qq5')</span>
                                <select class="form-control" name="city_id" id="merchant-city_id">
                                    <option selected disabled>@lang('system.qq5')</option>
                                    @foreach($trAreas as $item)
                                        <option @if(Auth::user()->city_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="country_title" value="Турция">
                                <input type="hidden" id="merchant-city_title" name="city_title" value="">
                            </div>

                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.tax_office')</span>
                                {!! $form["tax_office"] !!}
                            </div>

                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.type_invoice')</span>
                                {!! $form["type_invoice"] !!}
                            </div>

                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.address_invoice')</span>
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
                                    <span>@lang('merchant.form.legal_address_street')</span>
                                    {!! $form["legal_address_street"] !!}
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="w-50">
                                        <span>@lang('merchant.form.legal_address_number')</span>
                                        {!! $form["legal_address_number"] !!}
                                    </div>
                                    <div class="w-50">
                                        <span>@lang('merchant.form.legal_address_office')</span>
                                        {!! $form["legal_address_office"] !!}
                                    </div>
                                </div>
                            </div>
                            <div class="inputs-wrap">
                                <div class="checkbox-wrap">
                                    <label>
                                        <input type="checkbox" @if($equal== 1) checked @endif  data-check="{{$equal}}" id="equal_addresses">
                                        <span></span>
                                        <p>@lang('system.qq14')</p>
                                    </label>
                                </div>
                            </div>
                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.address_return')</span>
                                <div class="w-100 mt-2">
                                    <span>@lang('merchant.form.city')</span>
                                    <select class="form-control" name="city" id="merchant-city">
                                        <option selected disabled>@lang('merchant.form.city')</option>
                                        @foreach($trAreas as $item)
                                            <option @if($addressCity === $item->name) selected @endif value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-100 mt-2">
                                    <span>@lang('merchant.form.street')</span>
                                    {!! $form["street"] !!}
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="w-50">
                                        <span>@lang('merchant.form.number')</span>
                                        {!! $form["number"] !!}
                                    </div>
                                    <div class="w-50">
                                        <span>@lang('merchant.form.office')</span>
                                        {!! $form["office"] !!}
                                    </div>
                                </div>
                            </div>

                            <button class="green-btn" type="submit">@lang('system.save')</button>
                            <div class="inputs-wrap">
                                <span>@lang('merchant.form.dt_reg') </span>
                                <input id="input-11" type="text" autocomplete="on" name="input-11"  value="{{\Carbon\Carbon::parse(Auth::user()->created_at)->format('Y-m-d')}}" disabled class="gray-bg">
                            </div>
                        </div>
                        <div class="right">
                            <h5>@lang('system.m31')</h5>
                            <div class="admin-info">
                                <div class="inputs-wrap">
                                    <span>@lang('body.first_name')</span>
                                    {!! $form["first_name"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('body.last_name')</span>
                                    {!! $form["last_name"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('body.patronymic')</span>
                                    {!! $form["patronymic"] !!}
                                </div>
                                <div class="inputs-wrap">
                                    <span>@lang('system.lk10')</span>
                                    {!! $form["phone"] !!}
                                    <span>@lang('system.m32')</span>
                                    {!! $form["email"] !!}
                                </div>
                            </div>
                            <div class="sales-categories">
                                <h5>@lang('system.m33')</h5>
                                <div class="inputs-wrap">
                                    {!! $form["sale_category"] !!}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
