@extends("body")
@section("title", "Каталог")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            <div class="client-basket">
                <div class="top">
                    <a href="javascript:history.back()" class="back"><img src="/img/back-icon.png" alt=""></a>
                    <h3>@lang('cart.yourCart')</h3>
                    <span id="count-items-cart">{{ count($cart['items']) }} </span>
                    <p  style="cursor: pointer;" class="clear  clear-cart">@lang('cart.clear')</p>
                </div>
                <p class="cart-contents">@lang('cart.warningCart')</p>
                <div class="table-scroll-wrap">
                    <table  cellspacing="1" class="merchant-goods-table order-list-table pb15">
                        <thead>
                        <tr>
                            <th style="width: 13%">@lang('cart.photoCart')</th>
                            <th style="width: 20%">@lang('cart.marka')</th>
                            <th style="width: 15%">@lang('cart.size')</th>
                            <th style="width: 15%">@lang('system.color')</th>
                            <th style="width: 15%">@lang('cart.count')</th>
                            <th style="width: 15%">@lang('catalog_item.form.price')</th>
                            <th style="width: 17%">@lang('cart.total')</th>
                            <th style="width: 5%"></th>
                        </tr>
                        </thead>
                        <tbody id="product-table-cart">
                        @foreach($cart["items"] as $k=>$i)
                            <tr class="line" data-key="{{ $i->key }}color{{$i->color}}">
                                <td>
                                    <div class="big-img"><img src="{{ $i->image() }}" alt=""></div>
                                </td>
                                <td>
                                    <div class="brand">
                                        <b>{{$i->lang("name")}}</b>
                                        {{--                                        <p><span>Цвет: серо-синий</span><span>Бренд: Colin’s</span></p>--}}
                                        <div class="texts">
                                            <span>@lang('cart.acrticul') {{ $i->article }}</span>
                                            <p><span>@lang('cart.seller')</span> <span class="green">{{  $i->user->name ?? '' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="size-color-big">
                                        <button>{{ $i->size }}</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="size-color-big">
                                        <button>{{ \App\Models\CatalogCharacteristicItem::where('id','=',$i->color )->first()->{'name_'.app()->getLocale()} }}</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-count">
                                        <button type="button" class="btn-minus cart-minus"><span class="minus">-</span>
                                        </button>
                                        <input type="text" class="input-number" value="{{ $i->count }}" min="1">
                                        <button type="button" class="btn-plus cart-plus"><span>+</span></button>
                                    </div>
                                </td>
                                <td>
                                    <p class="texts price-product" data-price="{{$i->price}}">{{ $i->price }} {{--<br><span class="red line">16 500 ₺l</span>--}}</p>
                                </td>
                                <td><span class="total" data-price="{{$i->total}}">{{ $i->total }}</span> </td>
                                <td>
                                    <div class="buttons">
                                        <a class="cart-del"><img src="/img/close-icon.svg" alt=""></a>
                                        <a href="#" class="arrow"><img src="/img/arrow-right-small.svg" alt=""></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="table-bottom-line">
                    <p></p>
                    <div class="itog">
                        <p>@lang('cart.total'): <span class="dark"><span class="cart-price" id="total-price-order">{{ $cart["price"] }}</span> </span></p>
                        {{--<p>Ваша экономия: <span class="green">1 521.00 ₺l</span></p>--}}
                        {{--<a href="#" class="green-btn">Перейти к оформлению</a>--}}
                    </div>
                </div>
            </div>
            @if(Auth::check())
                <div class="client-checkout">
                    <h5>@lang('cart.newOrder')</h5>
                    @if(isset($cart['items'][0]))
                    <div class="client-checkout-items form-ajax">
                        <form class="client-data-info form-ajax-client-data-info" action="{{route('cart.newOrder')}}" method="POST">
                            <div class="info info1">
                                <h4>@lang('cart.personalData')</h4>
                                <span class="input-title">@lang('cart.fio')</span>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="surname" value="{{\Illuminate\Support\Facades\Auth::user()->s_name ?? '' }}" placeholder="@lang('cart.last_name')">
                                    @if ($errors->has('surname'))
                                        <span class="text-danger">{{ $errors->first('surname') }}</span>
                                    @endif
                                    <div class="error-field-text error-surname"></div>
                                </div>
                                <div class="form-group">
                                    <input  class="@error('name') is-invalid @enderror" type="text" name="name" value="{{Auth::user()->name  ?? ''}}" placeholder="@lang('cart.first_name')">
                                    @error('name')
                                    <div class="alert alert-danger">{{ $message['name'] }}</div>
                                    @enderror
                                    <div class="error-field-text error-name"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="middle_name" value="{{\Illuminate\Support\Facades\Auth::user()->m_name ?? ''}}" placeholder="@lang('cart.patronymic')">
                                    @error('middle_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="error-field-text error-middle_name"></div>
                                </div>

                                @if(Auth::user()->phone)
                                    <input type="text" disabled value="{{ Auth::user()->phone }}">
                                    <input style="visibility: hidden; width: 0; height: 0; opacity: 0" type="text" name="phone" value="{{ Auth::user()->phone }}">
                                @else
                                    <div class="phone-number-wrap">
                                        <span class="input-title">@lang('cart.phone')</span>
                                        <input type="text" name="phone" id="input-phone" required placeholder="Номер телефона:">
                                        @error('phone')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="error-field-text error-phone"></div>
                                    </div>
                                @endif

                                <span class="input-title">@lang('cart.email')</span>
                                <div class="form-group">
                                    <input  type="email" value="{{\Illuminate\Support\Facades\Auth::user()->email ?? ''}}" name="email" placeholder="E-mail">
                                    @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="error-field-text error-email"></div>
                                </div>
                            </div>
                            <div class="info info2">
                                <h4>@lang('cart.addressDelivery') <a class="green-text ml-2" href="{{route('customer.self')}}">@lang('customer.orders.change') </a></h4>
                                <div class="customer-addresses">
                                    <div class="inputs-wrap">
                                        <span class="input-title">@lang('customer.form-label.fl1')</span>
                                        <select readonly class="form-control" name="country_id" id="profile-country_id">
                                            @foreach($countries as $item)
                                                @if(Auth::user()->country_id === $item->id)
                                                    <option @if(Auth::user()->country_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name_ru}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="inputs-wrap">
                                        <span class="input-title">@lang('customer.form-label.fl2')</span>
                                        <select readonly class="form-control" name="region_id" id="profile-region_id">
                                            @if($regions)
                                                @foreach($regions as $item)
                                                    @if(Auth::user()->region_id === $item->id)
                                                        <option @if(Auth::user()->region_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="inputs-wrap">
                                        <span class="input-title">@lang('customer.form-label.fl3')</span>
                                        <select readonly class="form-control" name="area_id" id="profile-area_id">
                                            @if($areas)
                                                @foreach($areas as $item)
                                                    @if(Auth::user()->area_id === $item->id)
                                                        <option @if(Auth::user()->area_id === $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="inputs-wrap">
                                        <span class="input-title">@lang('customer.form-label.fl4')</span>
                                        <select readonly class="form-control" name="city_id" id="profile-city_id">
                                            @if($cities)
                                                @foreach($cities as $item)
                                                    @if(Auth::user()->city_id === $item->id)
                                                        <option selected value="{{$item->id}}">{{$item->name}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="inputs-wrap">
                                        <span class="input-title">@lang('customer.form-label.fl5')</span>
                                        <select readonly class="form-control" name="postcode_id" id="profile-postcode">
                                            @if($postCodes)
                                                @foreach($postCodes as $item)
                                                    @if(Auth::user()->postcode_id === $item->id)
                                                        <option @if(Auth::user()->postcode_id === $item->id) selected @endif value="{{$item->id}}">{{$item->title}}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                <option disabled selected id="profile-postcode__def">@lang('customer.form-label.fl5')</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="street" value="{{Auth::user()->address_invoice ?? ''}}" placeholder="@lang('cart.street')">
                                    @error('street')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="error-field-text error-street"></div>
                                </div>
                                <div class="inputs">
                                    <input type="text" name="house_number" value="{{\Illuminate\Support\Facades\Auth::user()->house_number ?? ''}}" placeholder="@lang('cart.homeNumber')">
                                    @error('house_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <input type="text" name="room" value="{{\Illuminate\Support\Facades\Auth::user()->room ?? ''}}" placeholder="@lang('cart.appartments')">
                                    @error('room')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <textarea name="comment" placeholder="@lang('cart.commentOrder')"></textarea>
                            </div>
                            <div class="info info3">
                                <h4>@lang('cart.info')</h4>
                                <span class="input-title">@lang('cart.typePay')</span>
                                <div class="payment-method">
                                    <div class="payment-method__block" @if(!$cartCheckCount) style="display: none" @endif>
                                        <div class="deliverey">
                                            <p>@lang('cart.delivery')<b id="card-delivery-price">{{ isset(session()->get('deliveryPrices')['total']) ? session()->get('deliveryPrices')['total'] : '' }} <b>₸</b></b> </p>
                                            <p><span class="gray">@lang('cart.deliverySomeTime')</span>
                                                <span class="red">{{\Carbon\Carbon::parse()->now()->addDays(14)->format('d')}} - {{\Carbon\Carbon::parse()->now()->addDays(15)->format('d')}} {{\Carbon\Carbon::parse()->now()->addDays(15)->format('F')}}</span></p>
                                        </div>
                                        <input type="hidden" name="price" value="" id="price-order">

                                        <input type="hidden" readonly name="delivery" value="{{session()->get('deliveryPrices')['delivery'] ?? 0}}" id="price-delivery">
                                        <input type="hidden"  readonly name="deliveryTr"  value="{{session()->get('deliveryPrices')['deliveryTr'] ?? 0}}">

                                        <p>ИТОГО <span class="bold" id="total-order-price-data"></span></p>
                                        {{--<p>Ваша экономия:<span class="green">1 521.00 ₺l</span></p>--}}
                                        <button
                                            type="submit"
                                            id="new-order-button"
                                            class="green-btn">
                                            @lang('cart.toPay')
                                        </button>
                                    </div>
                                    <span
                                        @if($cartCheckCount) style="display: none" @endif
                                        id="get-del-price"
                                        class="green-btn">
                                        Рассчитать стоимость доставки
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="info-items client-info-items">
                    <a href="/" class="info-item">
                        <img src="/img/icon6.svg" alt="">
                        <span>@lang('cart.toCatalog')</span>
                    </a>
                    <a href="/customer/favorites" class="info-item">
                        <img src="/img/icon7.svg" alt="">
                        <span>@lang('cart.toFavorite')</span>
                    </a>
                    <a href="/customer/orders" class="info-item">
                        <img src="/img/icon2.svg" alt="">
                        <span>@lang('cart.myOrders')</span>
                    </a>
                </div>
            @else
                <div class="d-flex justify-content-center align-items-center">
                    <a href="#entrance-popup"  onclick="openPopUpRegister()"  id="UserRegister"  class="red-btn popup">{{ __("menu.user.reg") }}</a>
                    <p class="ml-2 mr-2">@lang('cart.or')</p>
                    <a href="#entrance-popup" onclick="openPopUpLogin()" id="UserLogin" class="green-btn popup">{{ __("menu.user.login") }}</a>
                    <p class="ml-2">@lang('cart.forRegister')</p>
                </div>
            @endif

        </div>
    </div>

@endsection
