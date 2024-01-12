@extends("body")
@section("title", "Order")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="customer-panel customer-orders__wrap">
                <div class="customer-orders__header">
                    <a href="{{ route("merchant.orders") }}" class="orders__header_back">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle opacity="0.7" cx="12.5" cy="12.5" r="12.5" fill="#DBDBDB"/>
                            <path opacity="0.7" d="M13.4455 16.8179L9.5957 13.0455L13.4455 9.27333L14.5957 10.4511L11.9479 13.0455L14.5957 15.6401L13.4455 16.8179Z" fill="#2D2929"/>
                        </svg>
                    </a>
                    <div class="orders__header_title">@lang('customer.orders.information_about_order')</div>
                    <div class="orders__header_count">{{ $order[0]['id'] }}</div>

                    @if($order[0]['status_int'] == 7)
                        <div class="orders__header_title text-red">@lang('customer.orders.status.status_0')</div>
                    @endif
                    @if($order[0]['barcode'])
                        <div class="orders__header_title">{{ $order[0]['barcode']  }}</div>
                    @endif
                    <!--<a href="#order-reviews-popup" class="orders__header_review popup">@lang('customer.orders.leave_feedback')</a>-->
                </div>
                <div class="customer-orders__body">
                    <div class="table-scroll-wrap">
                        <table cellspacing="1" class="merchant-goods-table">
                            <thead>
                            <tr>
                                <th style="width: 15%">@lang('customer.orders.vendor_code')</th>
                                <th style="width: 25%">@lang('customer.orders.product_name_brand')</th>

                                <th style="width: 15%">@lang('cart.size')</th>
                                <th style="width: 15%">@lang('system.color')</th>

                                <th style="width: 10%">@lang('customer.orders.quantity')</th>
                                <th style="width: 13%">@lang('customer.orders.total')</th>
                                <th style="width: 4%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td><span>{{ $item['article'] }}</span></td>
                                    <td>
                                        <span>
                                            <img height="100px" src="{{$item['image'][0]['file'] ?? '/img/logo.svg'}}" alt=""> &nbsp;
                                        </span>
                                        <span class="name">{{ $item['name'] }}</span>
                                        <span class="brand">{{ $item['brand'] }}</span>
                                    </td>
                                    <td><span>{{ $item['size'] }}</span></td>
                                    <td><span>{{  \App\Models\CatalogCharacteristicItem::where('id','=',$item['color'] )->first()->{'name_'.app()->getLocale()} ?? '' }}</span></td>
                                    <td><span>{{ $item['count'] }}</span></td>
                                        <td><span id="price-product-tr">{{ number_format($item['price'], 2, '.', ' ') }} ₺l</span>
                                        <span id="price-product-kz">{{$item['price_tenge']}} ₸</span></td>
                                    <td>
                                        <a href="{{ route("shop.item", $item['catalog_item_id']) }}" class="arrow"><img src="/img/arrow-right-small.svg" alt=""></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="customer-orders__info">


                        <div class="customer-orders__info_left">
                            <div class="order-info__header">
                            @if(!empty($order[0]['status'][0]))
                                <div class="order-info__header_left">
                                    @lang('customer.orders.order_status_l'): <span class="green-text">{{ $order[0]['status'][0] }}</span>
                                </div>
                                @endif
                                <div class="order-info__header_right">
                                    @lang('customer.orders.track_your_order')
                                </div>
                            </div>
                            <div class="order-info__body">
                                <div class="order-info__progress">
                                	@if(!empty($order[0]['status'][1]))
                                        <div class="order-info__progress_done" style="width: {{$order[0]['status'][1]}}"></div>
                                    @endif
                                </div>
                                <div class="order-info__progress_statuses">
                                    <ul>
                                        <li>
                                            <div class="green-text">@lang('customer.orders.status.status_1')</div>
                                            <span>{{ $order[0]['created_at'] }}</span>
                                        </li>
                                        <li>
                                            <div>@lang('customer.orders.status.status_2')</div>
                                            @if($order[0]['status_int'] == 2)
                                                <span>{{ $order[0]['updated_at'] }}</span>
                                            @endif
                                        </li>
                                        <li>
                                            <div>@lang('customer.orders.status.status_3')</div>
                                            @if($order[0]['status_int'] == 3)
                                                <span>{{ $order[0]['updated_at'] }}</span>
                                            @endif
                                        </li>
                                        <li>
                                            <div>@lang('customer.orders.status.status_4')</div>
                                            @if($order[0]['status_int'] == 4)
                                                <span>{{ $order[0]['updated_at'] }}</span>
                                            @endif
                                        </li>
                                        <li>
                                            <div>@lang('customer.orders.status.status_5')</div>
                                            @if($order[0]['status_int'] == 5)
                                                <span>{{ $order[0]['updated_at'] }}</span>
                                            @endif
                                        </li>
                                        <li>
                                            <div>@lang('customer.orders.status.status_6')</div>
                                            @if($order[0]['status_int'] == 6)
                                                <span>{{ $order[0]['updated_at'] }}</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="order-info__footer">
                                @lang('customer.orders.delivery_address'):
                                <span>
                                    {{ $order[0]['country_name'] }},
                                    г. {{ $order[0]['city_name'] }},
                                    ул. {{ $order[0]['street'] }},
                                    д. {{ $order[0]['house_number'] }},
                                    кв. {{ $order[0]['room'] }}
                                </span>

                            </div>
                        </div>
                        <div class="customer-orders__info_right">
                            <div class="order-price">
                                <div class="order-price__product">
                                    @lang('customer.orders.total_l'):
                                    <span> {{ $item['price_tenge']}} ₸</span>
                                </div>
                                <div class="order-price__delivery">
                                    @lang('customer.orders.delivery'): <span>{{ $order[0]['delivery_price'] }} ₸</span>
                                </div>
                                <div class="order-price__total">
                                    @lang('customer.orders.total_with_delivery'):

                                    <span>{{ $order[0]['total'] }} ₸ </span>
                                </div>
                            </div>


                            @if(Auth::user()->role == 'merchant' && $order[0]['status_int'] == 1)
                                <a data-question="{{ trans('system.question100') }}" href="{{ route("merchant.onWay",['id' => $order[0]['id']]) }}" style="width: 100%;height: 40px;display: flex;align-content: center;align-items: center;justify-content: center;" class="green-btn product-btn1">@lang('system.setOnWay')</a>
                            @endif
                            @if(Auth::user()->role == 'admin' && ($order[0]['status_int'] == 1 || $order[0]['status_int'] == 2 || $order[0]['status_int'] == 3  ))
                                <a data-question="{{ trans('system.question100') }}" href="{{ route('merchant.cancel', $order[0]['id'] ) }}" class="order__canceled">@lang('customer.orders.cancel_the_order')</a>
                            @endif
                           <!-- <div class="order__canceled_title text-center">@lang('customer.orders.with_a_refund')</div> -->
                        </div>
                    </div>
                </div>
            </div>
            @include('customer_bottom')
        </div>
    </div>
    <div id="order-reviews-popup" class="mfp-hide white-popup mfp-with-anim main-popup">
        <div class="popup-body">
            <button title="Close (Esc)" type="button" class="mfp-close"></button>
            <div class="order-reviews__body">
                <div class="order-reviews__title">@lang('customer.orders.add_feedback_to_the_order')</div>
                <div class="order-reviews__item" id="reviews-item" data-item="{{$order[0]['id']}}">{{$order[0]['id']}}</div>
                <div id="reviews-rating" class="rating">
                    <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                    <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                    <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                    <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                    <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                </div>
                <small class="error-reviews">@lang('customer.orders.choose_rating')</small>
                <div class="input-box">
                    <textarea name="" id="reviews-subject" cols="10" rows="5" placeholder="{{ __('customer.orders.write_a_review') }}"></textarea>
                </div>
                <button id="reviewSubmit" class="green-btn">@lang('customer.orders.publish')</button>
            </div>
        </div>
    </div>
@endsection
