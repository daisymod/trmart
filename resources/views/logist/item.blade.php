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
                    <div class="table-control">
                        @if(count($orders))
                            <form method="GET" action="{{ route("logist.export.item", ['id' => $formId]) }}">
                                <button id="orderExport-send" class="green-btn product-btn1">Экспорт excel</button>
                            </form>
                        @endif
                        @if(count($addedOrders))
                            <form class="logist-orders__add_list" method="GET" action="{{ route('logist.add.item') }}">
                                <select name="selected[]" id="">
                                    @foreach($addedOrders as $item)
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="selected[]" value="{{$formId}}">
                                <button>Добавить</button>
                            </form>
                        @endif
                        @if(count($orders))
                            <form method="GET" action="{{ route("logist.update.status.item", ['id' => $formId]) }}">
                                <button
                                    class="green-btn product-btn1"
                                    onclick="return confirm('Вы уверены, что хотите отправить форму 103 в архив? Напоминаем, что это действие нужно совершать когда Акт приема-передачи отправлений с Казпочтой подписан. Это действие нельзя отменить')"
                                >Передано в Казпочту</button>
                            </form>
                        @endif
                    </div>
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
                                <th style="width: 7%">@lang('logist.orders.loi12')</th>
                                <th style="width: 10%">@lang('logist.orders.loi9')</th>
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

                                    <td>
                                        <input class="logist-table__input" type="text" value="{{ $order['real_weight'] }}">
                                    </td>
                                    <td><span>{{ $order['created_at'] }}</span></td>
                                    <td><span>
                                             @if($order['status'] == 7)
                                                @lang('customer.orders.status.status_0')
                                            @else
                                                @lang('customer.orders.status.status_'.$order['status'])
                                            @endif
                                        </span></td>
                                    <td style="display: flex; justify-content: flex-end;">
                                        @if(isset($order['barcode']))
                                            <form method="GET" action="{{ route("logist.download.pdf", ['id' => $order['id']]) }}">
                                                <button class="pdf-download" title="Скачать адресный бланк"><i class="fa-solid fa-download"></i></button>
                                            </form>
                                        @endif
                                        <span class="calc-delivery" title="Обновить сумму доставки"><i class="fa-solid fa-rotate-right"></i></span>
                                        <form method="GET" action="{{ route("logist.get.letter", ['id' => $order['id']]) }}">
                                            <button class="letter-order" title="Получить Трек-номер"><i class="fa-solid fa-file-alt"></i></button>
                                        </form>
                                        <form method="GET" action="{{ route("logist.delete.item", ['id' => $formId, 'form' => $order['id']]) }}">
                                            <button class="del-delivery" title="Удалить"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
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
