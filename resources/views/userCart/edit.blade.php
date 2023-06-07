@extends("body")
@section("title", "User Cart")
@section("content")
    <div class="container">
        <div class="card">
            <div class="personal-area">
                <div class="wrapper">


                    <div class="card-header"><div class="menu">@include("menu")</div></div>
                    <div class="client-basket">
                        <div class="table-scroll-wrap">
                            <table cellspacing="1" class="merchant-goods-table order-list-table pb15">
                                <thead>
                                <tr>
                                    <th style="width: 13%">@lang('cart.photoCart')</th>
                                    <th style="width: 20%">@lang('cart.marka')</th>
                                    <th style="width: 15%">@lang('cart.size')</th>
                                    <th style="width: 15%">@lang('system.color')</th>
                                    <th style="width: 15%">@lang('cart.count')</th>
                                    <th style="width: 15%">@lang('cart.price')</th>
                                    <th style="width: 17%">@lang('cart.total')</th>
                                    <th style="width: 5%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($record as $k=>$i)
                                    @if($i['user_id'] == Auth::user()->id || Auth::user()->role == 'admin')
                                        <tr class="line" data-key="{{ $i['key'] }}">
                                        <td>
                                            <div class="big-img"><img src="{{$i[0]['image_url']}}" alt=""></div>
                                        </td>
                                        <td>
                                            <div class="brand">
                                                <b>{{$i['name_ru']}}</b>
                                                <div class="texts">
                                                    <span>@lang('cart.acrticul') {{ $i['article'] }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="size-color-big">
                                                <span>{{ $i['size'] }}</span>
                                            </div>
                                        </td>
                                            <td>
                                                <div class="size-color-big">
                                                    <span>{{ \App\Models\CatalogCharacteristicItem::where('id','=',$item['color'] )->first()->{'name_'.app()->getLocale()}}}</span>
                                                </div>
                                            </td>
                                        <td>
                                            <div class="product-count">
                                                <input type="text" class="input-number" value="{{ $i['count'] }}" min="1">
                                            </div>
                                        </td>
                                        <td>
                                            <p>{{ $i['price'] }} </p>
                                        </td>
                                        <td><span >{{ $i['total'] }}</span> </td>
                                    </tr>
                                    @endif
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
