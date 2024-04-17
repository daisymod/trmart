@extends("body")
@section("title", "Покупки")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="customer-panel customer-orders__wrap">
                <form method="GET" action="{{route('customer.orders')}}">
                    <div class="customer-orders__header">
                        <div class="orders__header_title">@lang('customer.orders.your_orders')</div>
                        <div class="orders__header_count">{{ count($data) }}</div>
                        <div class="form-group sm">
                            <select name="orders_status" id="orders_status" class="form-control">
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 'all')  ) selected @endif value="all">@lang('customer.orders.status.status_all')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 1)  ) selected @endif value="1">@lang('customer.orders.status.status_1')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 2)  ) selected @endif value="2">@lang('customer.orders.status.status_2')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 3)  ) selected @endif value="3">@lang('customer.orders.status.status_3')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 4)  ) selected @endif value="4">@lang('customer.orders.status.status_4')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 5)  ) selected @endif value="5">@lang('customer.orders.status.status_5')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 6)  ) selected @endif value="6">@lang('customer.orders.status.status_6')</option>
                                <option @if(isset($_GET['orders_status']) && ($_GET['orders_status'] == 7)  ) selected @endif value="7">@lang('customer.orders.status.status_0')</option>
                            </select>
                        </div>
                    </div>
                    <button class="green-btn product-btn1">@lang('system.find') </button>
                </form>


                <div class="customer-orders__body">
                    <div class="table-scroll-wrap">
                        <table cellspacing="1" class="merchant-goods-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%">@lang('customer.orders.order_number')</th>
                                    <th style="width: 14%">@lang('customer.orders.created_data')</th>
                                    <th style="width: 18%">@lang('customer.orders.will_be_delivered_remaining')</th>
                                    <th style="width: 7%">@lang('customer.orders.sum')</th>
                                    <th style="width: 7%">@lang('customer.orders.delivery')</th>
                                    <th style="width: 11%">@lang('customer.orders.discount')</th>
                                    <th style="width: 11%">@lang('customer.orders.order_status')</th>
                                    <th style="width: 7%"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach($data as $order)
                                <tr @if($order['status'] == 4) class="done" @endif>
                                    <td>
                                        @if($order['status'] == 4)<span class="dote"></span>@endif
                                        <span>{{ $order['id'] }}</span>
                                    </td>
                                    <td><span>{{ $order['created_at'] }}</span></td>
                                    <td>
                                        <span>{{ $order['delivery_dt_end'] }}</span> &nbsp;
                                        @if($order['status'] != 4)
                                            <span class="{{ $order['left_status'] == 0 ? 'red-text' : 'green-text' }}">{{ $order['left'] }}</span>
                                        @endif
                                    </td>
                                    <td><span>{{ $order['price'] }} ₸</span></td>
                                    <td><span>{{ $order['delivery_sum'] ?? 0 }} ₸</span></td>
                                    <td><span>{{ $order['sale'] }} ₸</span></td>
                                    <td>
                                        <span class="{{ $order['status'] == 4 ? 'green-text' : 'red-text' }}">
                                            @if($order['status'] == 4)
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 0C2.68575 0 0 2.68575 0 6C0 9.31425 2.68575 12 6 12C9.31425 12 12 9.31425 12 6C12 2.68575 9.31425 0 6 0ZM9.183 9.183C8.33175 10.0313 7.20225 10.5 6 10.5C4.79775 10.5 3.66825 10.0313 2.817 9.183C1.96875 8.33175 1.5 7.20225 1.5 6C1.5 4.79775 1.96875 3.66825 2.817 2.817C3.66825 1.96875 4.79775 1.5 6 1.5C7.20225 1.5 8.33175 1.96875 9.183 2.817C10.0313 3.66825 10.5 4.79775 10.5 6C10.5 7.20225 10.0313 8.33175 9.183 9.183ZM8.088 3.687L5.262 6.82725L3.9465 5.529L2.892 6.5955L5.325 9L9.20175 4.68975L8.088 3.687Z" fill="#37C155"/>
                                                </svg>
                                            @endif
                                            @if($order['status'] == 7)
                                                    @lang('customer.orders.status.status_0')
                                            @else
                                                    @lang('customer.orders.status.status_'.$order['status'])
                                            @endif

                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route("customer.order", $order['id']) }}" class="arrow">
                                            @lang('customer.orders.more')
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.7" cx="12.5" cy="12.5" r="12.5" transform="matrix(-1 0 0 1 25 0)" fill="#DBDBDB"/>
                                                <path opacity="0.7" d="M11.5545 16.8179L15.4043 13.0455L11.5545 9.27333L10.4043 10.4511L13.0521 13.0455L10.4043 15.6401L11.5545 16.8179Z" fill="#2D2929"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('customer_bottom')
        </div>
    </div>
@endsection
