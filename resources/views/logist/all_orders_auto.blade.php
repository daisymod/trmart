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
                                    <button class="filter-group__btn" type="submit">@lang('system.takeChange')</button>
                                    <a class="filter-group__btn-red" href="{{ route('logist.orders') }}">Очистить</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if($errors->any())
                        {!! implode('', $errors->all('<div class="logist-orders__error">:message</div>')) !!}
                    @endif
                    @if(count($data))
                        <div class="table-scroll-wrap">
                                <table cellspacing="1" class="merchant-goods-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 1%"></th>
                                        <th style="width: 4%">@lang('logist.orders.loi10')</th>
                                        <th style="width: 4%">@lang('logist.orders.loi11')</th>
                                        <th style="width: 4%">@lang('logist.orders.loi4')</th>
                                        <th style="width: 4%">@lang('logist.orders.loi5')</th>

                                        <th style="width: 4%">delivery auto</th>

                                        <th style="width: 4%">@lang('logist.orders.loi7')</th>
                                        <th style="width: 4%">@lang('logist.orders.loi8')</th>
                                        <th style="width: 4%">@lang('logist.orders.loi12')</th>
                                        <th style="width: 12%"></th>
                                        <th style="width: 18%">@lang('logist.orders.loi9')</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    @foreach($data as $order)
                                        <tr>
                                            <td><input class="add-to__f103" name="orders[]" value="{{ $order['id'] }}" type="checkbox"></td>
                                            <td><span class="order-id"><a href="{{route('merchant.order',['id'=> $order['id'] ])}}">{{ $order['id'] }}</a></span></td>
                                            <td><span>{{ $order['merchant'] }}</span></td>
                                            <td><span>{{ $order['city_name'] }}</span></td>
                                            <td><span>{{ $order['price'] }} ₸</span></td>
                                            <td><span>{{ ($order['delivery_price'] ?? 0 + $order['tr_delivery_price'] ?? 0)  }} ₸</span></td>

                                            <td>
                                                <input class="logist-table__input" type="text" value="{{ $order['real_weight'] }}">
                                            </td>
                                            <td><span>{{ $order['created_at'] }}</span></td>
                                            <td><span> @if($order['status'] == 7)
                                                        @lang('customer.orders.status.status_0')
                                                    @else
                                                        @lang('customer.orders.status.status_'.$order['status'])
                                                    @endif </span></td>
                                            <td>
                                                <a href="{{route('logist.download_auto',['id' => $order['id']])}}" class="filter-group__btn">
                                                    Скачать адресный бланк
                                                </a>
                                            </td>
                                            <td style="text-align: right">
                                               <form method="get" action="{{route('logist.change_status',['id' => $order['id']])}}">
                                                    @csrf
                                                    <select class="logist-table__input" name="status">
                                                        <option @if($order['status'] == 0 ) selected @endif value="0"> @lang('customer.orders.status.status_0')</option>
                                                         @for($i = 1; $i<7;$i++)
                                                            <option @if($i == $order['status']) selected @endif value="{{$i}}"> @lang('customer.orders.status.status_'.$i)</option>
                                                        @endfor
                                                    </select>
                                                   <button class="filter-group__btn" type="submit">@lang('system.takeChange')</button>
                                               </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                    @else
                        <div>Нет заказов</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="top"><i class="fa-solid fa-arrow-up"></i></span>
@endsection
