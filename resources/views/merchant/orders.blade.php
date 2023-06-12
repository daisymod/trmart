@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("merchant_top")
            <div class="customer-panel customer-orders__wrap">
                <div class="customer-orders__header merchant_orders">
                    <div>
                        <div class="orders__header_title">@lang('system.o1')</div>
                        <div class="orders__header_count">{{ count($data) }}</div>
                    </div>
                    <form method="GET" action="{{ route("merchant.orders") }}">
                        <div>
                            @if(Auth::user()->role == 'admin')
                                <div class="form-group sm">
                                    <select name="merchant" id="merchant" class="form-control">
                                        <option value="all">@lang('customer.orders.status.status_all')</option>
                                        @foreach($merchants as $merchant)
                                            <option @if((isset($_GET['merchant']) && ($_GET['merchant'] == $merchant->id))) selected @endif value="{{$merchant->id}}">{{$merchant->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group sm">
                                <select name="orders_status" id="orders_status" class="form-control">
                                    <option value="all">@lang('customer.orders.status.status_all')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 1))) selected @endif value="1">@lang('customer.orders.status.status_1')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 2))) selected @endif value="2">@lang('customer.orders.status.status_2')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 3))) selected @endif value="3">@lang('customer.orders.status.status_3')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 4))) selected @endif value="4">@lang('customer.orders.status.status_4')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 5))) selected @endif value="5">@lang('customer.orders.status.status_5')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 6))) selected @endif value="6">@lang('customer.orders.status.status_6')</option>
                                    <option @if((isset($_GET['orders_status']) && ($_GET['orders_status'] == 7))) selected @endif value="7">@lang('customer.orders.status.status_0')</option>
                                </select>
                            </div>

                            <div class="d-flex w-100 justify-content-between mb-5 mt-5" style="margin-bottom: 100px ;margin-top: 20px;display: flex; justify-content: space-between; width: 50%">
                                <label for="from">
                                    @lang('system.q1')
                                    @if( isset($_GET['from']))
                                        <input type="date" id="from-data" name="from" class="form-control" value="{{  \Carbon\Carbon::parse($_GET['from'])->format('Y-m-d')}}">
                                    @else
                                        <input type="date" id="from-data" name="from" class="form-control" value="{{ \Carbon\Carbon::now()->subDays(7)->format('Y-m-d')}}">
                                    @endif
                                </label>
                                <label for="to">
                                    @lang('system.q2')
                                    @if( isset($_GET['to']))
                                         <input type="date" id="to-data" name="to" class="form-control" value="{{\Carbon\Carbon::parse($_GET['to'])->format('Y-m-d')}}">
                                    @else
                                        <input type="date" id="to-data"  name="to" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d')}}">
                                    @endif
                                </label>
                            </div>

                            <button class="green-btn product-btn1">@lang('system.find') </button>
                        </div>
                    </form>
                    <form class="load-form-file-form" method="POST" action="{{ route("merchant.exportOrders") }}" enctype="multipart/form-data">
                            <input type="hidden" id="merchant-export" name="merchant" value="{{  isset($_GET['merchant']) ? $_GET['merchant'] : 'all' }}">
                            <input type="hidden" id="orders_status-export" name="orders_status" value="{{  isset($_GET['orders_status']) ? $_GET['orders_status'] : 'all' }}">
                            <input type="hidden" id="from-data-export" name="from" class="form-control" value="{{isset($_GET['from']) ? $_GET['from'] :  \Carbon\Carbon::now()->subDays(7)->format('Y-m-d')}}">
                            <input type="hidden" id="to-data-export"  name="to" class="form-control" value="{{ isset($_GET['to']) ? $_GET['to'] :  \Carbon\Carbon::now()->format('Y-m-d')}}">
                        {{ csrf_field() }}
                        <button class="green-btn product-btn1">@lang('system.o15') </button>
                    </form>


                </div>
                <div class="customer-orders__body">
                    <div class="table-scroll-wrap">
                        <table cellspacing="1" class="merchant-goods-table">
                            <thead>
                            <tr>
                                <th style="width: 10%">@lang('customer.orders.order_number')</th>
                                <th style="width: 14%">@lang('customer.orders.created_data')</th>
                                <th style="width: 18%">@lang('customer.orders.will_be_delivered_remaining')</th>
                                <th style="width: 7%">@lang('customer.orders.sum')</th>

                                @if(Auth::user()->role == 'admin')
                                    <th style="width: 7%">@lang('customer.orders.delivery_sum')</th>
                                @endif
                                <th style="width: 7%">@lang('customer.orders.sum_without_commission')</th>
                                <th style="width: 7%">@lang('customer.orders.commission')</th>
                                @if(Auth::user()->role == 'admin')
                                    <th style="width: 7%">@lang('customer.orders.delivery_sum1')</th>
                                @endif
                                <th style="width: 11%">@lang('customer.orders.discount')</th>
                                <th style="width: 11%">@lang('customer.orders.order_status')</th>

                                <th style="width: 7%"></th>
                            </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach($data as $order)
                                <tr @if($order['status'] == 6) class="done" @endif>
                                    <td>
                                        @if($order['status'] == 6)<span class="dote"></span>@endif
                                        <span>{{ $order['id'] }}</span>
                                    </td>
                                    <td><span>{{ \Carbon\Carbon::parse($order['created_at'])->format('Y-m-d') }}</span></td>
                                    <td>
                                        <span>{{ $order['delivery_dt_end'] }}</span> &nbsp;
                                        @if($order['status'] != 6)
                                            <span class="{{ $order['left_status'] == 0 ? 'red-text' : 'green-text' }}">{{ $order['left'] }}</span>
                                        @endif
                                    </td>
                                    <td><span>{{ $order['commission'][0]->total_price ?? 0 }} ₸</span></td>
                                    @if(Auth::user()->role == 'admin')
                                        <td><span>{{ $order['delivery_sum'] }} ₸</span></td>
                                    @endif

                                    <td><span>{{ $order['price']}} ₸</span></td>
                                    <td><span>{{ $order['commission'][0]->commission_price ?? 0 }} ₸</span></td>
                                    @if(Auth::user()->role == 'admin')
                                        <td><span>{{ $order['order_price'] }} ₸</span></td>
                                    @endif

                                    <td><span>{{ $order['sale'] }} ₸</span></td>
                                    <td>

                                        <select name="orders_status" data-status="{{$order['status']}}" class="form-control orders_status">
                                            <option value="1">@lang('customer.orders.status.status_1')</option>
                                            <option value="2">@lang('customer.orders.status.status_2')</option>
                                            <option value="3">@lang('customer.orders.status.status_3')</option>
                                            <option value="4">@lang('customer.orders.status.status_4')</option>
                                            <option value="5">@lang('customer.orders.status.status_5')</option>
                                            <option value="6">@lang('customer.orders.status.status_6')</option>
                                            <option value="7">@lang('customer.orders.status.status_0')</option>

                                        </select>
                                    </td>
                                    <td>
                                        <a href="{{ route("merchant.order", $order['id']) }}" class="arrow">
                                            @lang('customer.orders.more')
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.7" cx="12.5" cy="12.5" r="12.5" transform="matrix(-1 0 0 1 25 0)" fill="#DBDBDB"/>
                                                <path opacity="0.7" d="M11.5545 16.8179L15.4043 13.0455L11.5545 9.27333L10.4043 10.4511L13.0521 13.0455L10.4043 15.6401L11.5545 16.8179Z" fill="#2D2929"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach


                            <tr>
                                <td><strong>TOTAL</strong></td>
                                <td>-</td>
                                <td>-</td>
                                <td>{{$data->total_price}} ₸</td>
                                @if(Auth::user()->role == 'admin')
                                    <td>{{$data->sum_delivery_price}} ₸</td>
                                @endif
                                <td>{{$data->total_price_without_commission}} ₸</td>
                                <td>{{$data->total_commission}} ₸</td>
                                @if(Auth::user()->role == 'admin')
                                    <td>{{$data->total_price_order}} ₸</td>
                                @endif
                                <td>{{$data->total_sale_order}} ₸</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            @include('customer_bottom')
        </div>
    </div>
@endsection
