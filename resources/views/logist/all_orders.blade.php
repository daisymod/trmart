@extends("body")
@section("title", "Заказы")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="customer-panel customer-orders__wrap">
                <div class="customer-orders__header">
                    <div class="orders__header_title">@lang('customer.orders.your_orders')</div>
                    <div class="orders__header_count">{{ count($data) }}</div>
                </div>
                <div class="customer-orders__body">
                    <div class="customer-orders__filter">
                        <h4>Фильтр <small>Выбрано: <span class="checked-count">0</span></small></h4>
                        <div class="filter-column">
                            <div class="filter-group">
                                <span id="select-all__checked" class="select-all__checked">Выбрать все</span>
                                <span id="unselect-all__checked" class="unselect-all__checked">Убрать все</span>
                            </div>
                            <form method="GET" action="{{ route('logist.all_orders') }}">
                                <div class="filter-group">
                                    <input class="logist-table__input" style="width: 100px" value="{{$_GET['id'] ?? null}}" type="text" name="id"  placeholder="id">
                                    <input class="logist-table__input" style="width: 100px" value="{{$_GET['barcode'] ?? null}}"  type="text" name="barcode"  placeholder="barcode">
                                    <select style="width: 300px" name="orders_status" id="orders_status" class="form-control">
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 'all')  ) selected @endif value="all">@lang('customer.orders.status.status_all')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 1)  ) selected @endif value="1">@lang('customer.orders.status.status_1')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 2)  ) selected @endif value="2">@lang('customer.orders.status.status_2')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 3)  ) selected @endif value="3">@lang('customer.orders.status.status_3')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 4)  ) selected @endif value="4">@lang('customer.orders.status.status_4')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 5)  ) selected @endif value="5">@lang('customer.orders.status.status_5')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 6)  ) selected @endif value="6">@lang('customer.orders.status.status_6')</option>
                                        <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 7)  ) selected @endif value="7">@lang('customer.orders.status.status_0')</option>
                                    </select>
                                    <input type="date" name="start" value="{{ $start }}">
                                    <input type="date" name="end" value="{{ $end }}">
                                    <button class="filter-group__btn" type="submit">Применить</button>
                                    <a class="filter-group__btn-red" href="{{ route('logist.orders') }}">Очистить</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if($errors->any())
                        {!! implode('', $errors->all('<div class="logist-orders__error">:message</div>')) !!}
                    @endif
                    @if(count($data))
                        <form method="GET" action="{{ route("logist.to.collect") }}">
                            <div class="table-scroll-wrap">
                                <table cellspacing="1" class="merchant-goods-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 1%"></th>
                                        <th style="width: 7%">@lang('logist.orders.loi1')</th>
                                        <th style="width: 8%">@lang('logist.orders.loi10')</th>
                                        <th style="width: 10%">@lang('logist.orders.loi11')</th>
                                        <th style="width: 7%">@lang('logist.orders.loi4')</th>
                                        <th style="width: 7%">@lang('logist.orders.loi5')</th>

                                        <th style="width: 8%">@lang('logist.orders.loi6')</th>
                                        <th style="width: 7%">@lang('logist.orders.loi61')</th>

                                        <th style="width: 8%">@lang('logist.orders.loi6') - 1</th>
                                        <th style="width: 7%">@lang('logist.orders.loi61') - 1</th>

                                        <th style="width: 10%">@lang('item.code')</th>

                                        <th style="width: 10%">@lang('logist.orders.loi7')</th>
                                        <th style="width: 10%">@lang('logist.orders.loi8')</th>
                                        <th style="width: 7%">@lang('logist.orders.loi12')</th>
                                        <th style="width: 5%">@lang('logist.orders.loi9')</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    @foreach($data as $order)
                                        <tr>
                                            <td><input class="add-to__f103" name="orders[]" value="{{ $order['id'] }}" type="checkbox"></td>
                                            <td><span class="order-id"><a href="{{route('merchant.order',['id'=> $order['id'] ])}}">{{ $order['id'] }}</a></span></td>
                                            <td><span>{{ $order['article'] }}</span></td>
                                            <td><span>{{ $order['merchant'] }}</span></td>
                                            <td><span>{{ $order['city_name'] }}</span></td>
                                            <td><span>{{ $order['price'] }} ₸</span></td>
                                            <td><span>{{ $order['delivery_price'] }} ₸</span></td>
                                            <td><span>{{ $order['tr_delivery_price'] }} ₸</span></td>

                                            <td><span class="logist-table__dp">{{ $order['delivery_kz_weighing'] }} ₸</span></td>
                                            <td><span class="logist-table__tdp">{{ $order['delivery_tr_weighing'] }} ₸</span></td>
                                            <td><span>{{ $order['barcode'] }} </span></td>
                                            <td>
                                                <input class="logist-table__input" type="text" value="{{ $order['real_weight'] }}">
                                            </td>
                                            <td><span>{{ $order['created_at'] }}</span></td>
                                            <td><span> @if($order['status'] == 7)
                                                        @lang('customer.orders.status.status_0')
                                                    @else
                                                        @lang('customer.orders.status.status_'.$order['status'])
                                                    @endif </span></td>
                                            <td style="text-align: right">
                                                @if($order['status'] < 4)
                                                    <span class="calc-delivery"><i class="fa-solid fa-rotate-right"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @else
                        <div>Нет заказов</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="top"><i class="fa-solid fa-arrow-up"></i></span>
@endsection
