@extends("body")
@section("title", "Покупки")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="customer-panel customer-orders__wrap">
                <div class="customer-orders__header">
                    <div class="orders__header_title">Форма 103 <small>({{ $formId }})</small></div>
                    <div class="orders__header_count">{{ count($orders) }}</div>
                </div>
                <div class="customer-orders__body">
                    @if($errors->any())
                        {!! implode('', $errors->all('<div class="logist-orders__error">:message</div>')) !!}
                    @endif
                        <div class="table-scroll-wrap">
                            <table cellspacing="1" class="merchant-goods-table">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">№</th>
                                    <th style="width: 7%">@lang('logist.orders.loi1')</th>
                                    <th style="width: 10%">@lang('logist.orders.loi2')</th>
                                    <th style="width: 8%">@lang('logist.orders.loi10')</th>
                                    <th style="width: 10%">@lang('logist.orders.loi11')</th>
                                    <th style="width: 7%">@lang('logist.orders.loi4')</th>
                                    <th style="width: 7%">@lang('logist.orders.loi5')</th>

                                    <th style="width: 8%">@lang('logist.orders.loi6')</th>
                                    <th style="width: 7%">@lang('logist.orders.loi61')</th>

                                    <th style="width: 8%">@lang('logist.orders.loi6') - 1</th>
                                    <th style="width: 7%">@lang('logist.orders.loi61') - 1</th>

                                    <th style="width: 10%">@lang('logist.orders.loi7')</th>
                                    <th style="width: 10%">@lang('logist.orders.loi8')</th>
                                </tr>
                                </thead>
                                <tbody id="table-body">
                                @foreach($orders as $key => $order)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td><span class="order-id">{{ $order['id'] }}</span></td>
                                        <td><span>{{ $order['barcode'] }}</span></td>
                                        <td><span>{{ $order['article'] }}</span></td>
                                        <td><span>{{ $order['merchant'] }}</span></td>
                                        <td><span>{{ $order['city_name'] }}</span></td>
                                        <td><span>{{ $order['price'] }} ₸</span></td>
                                        <td><span>{{ $order['delivery_price'] }} ₸</span></td>
                                        <td><span>{{ $order['tr_delivery_price'] }} ₸</span></td>

                                        <td><span class="logist-table__dp">{{ $order['delivery_kz_weighing'] }} ₸</span></td>
                                        <td><span class="logist-table__tdp">{{ $order['delivery_tr_weighing'] }} ₸</span></td>

                                        <td><span>{{ $order['real_weight'] }}</span></td>
                                        <td><span>{{ $order['created_at'] }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <span id="top"><i class="fa-solid fa-arrow-up"></i></span>
@endsection
