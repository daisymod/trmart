@extends("body")
@section("title", "Каталог")
@section("content")

    <div class="detail-wrap">
        <div class="wrapper">
            <ul class="breadcrumbs-list">
                <li><a href="/">@lang('menu.index')</a></li>
                @foreach($breadcrumbs as $k=>$i)
                    <li><span><a href="{{ route("shop.list", $i->id) }}">{{ $i->{'name_'.app()->getLocale()} }}</a></span></li>
                @endforeach
            </ul>
            <div class="top">
                <div class="left">
                    <h2>
                        <a href="{{ route("shop.list", $catalog->id ?? 1) }}" class="back"><img src="/img/back-icon.png" alt=""></a>{{ $record->lang("name") }}
                    </h2>
                    <div class="info">
                        <p>@lang('item.article'): <span>{{ $record->article }}</span></p>
                        <div class="review">
                            <div class="rating-wrap">
                                <div class="stars" data-stars="{{number_format($rating, 2, '.', ',')}}">
                                    <svg height="25" width="23" class="star rating" data-rating="1">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="2">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="3">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="4">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="5">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                </div>
                            </div>
                            <a href="#" style="cursor: initial">{{$count_feedback}} @lang('products.feedbacks')</a>
                        </div>


                    </div>
                </div>
                <a
                    @if (Auth::check())
                    href="javascript:void(0);"
                    id="addFavorites"
                    class="favorites {{$isFavorite ? 'has' : ''}}"
                    data-item="{{ $record->id }}"
                    @else
                    href="#entrance-popup"
                    class="favorites popup"
                    @endif
                    >
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http:/www.w3.org/2000/svg">
                        <path d="M9.71924 1.08847C8.59837 1.08847 7.60822 1.65118 7.01653 2.5086C7.01653 2.5086 6.81674 2.80757 6.49986 2.80757C6.18326 2.80757 5.98347 2.5086 5.98347 2.5086C5.39149 1.65118 4.40192 1.08847 3.28076 1.08847C1.46873 1.08847 0 2.5572 0 4.3698C0 4.7978 0.0841222 5.20506 0.233041 5.57991C1.15213 8.43381 5.26275 11.789 6.49986 11.9115C7.73725 11.789 11.8473 8.43381 12.767 5.57991C12.9159 5.20534 13 4.7978 13 4.3698C13 2.5572 11.5313 1.08847 9.71924 1.08847Z"/>
                    </svg>
                    <span>@lang('products.toFavorite')</span>
                </a>
            </div>

                <div class="detail-sliders" id="details-slider">
                    @php $k = 0; @endphp
                    @foreach($color as $gallery)
                    <div class="left slider-left">
                        <div class="detail-slider-wrap">
                            <div class="big-slider-wrap big-slider-wrap-data-{{$k}}">
                                <button style="left:-13px !important;" type="button" class="arrow arrow-prev">
                                    <img src="/img/slider-arrow-left.png" alt="">
                                </button>
                                <div class="big-slider" id="big-slider-{{$k}}">
                                    @if($gallery->image != null && $gallery->images() != null)
                                        @foreach($gallery->images() as $image)
                                           <div class="item"><img src="{{ $image["img"] ?? '#' }}" alt=""></div>
                                        @endforeach
                                    @endif

                                </div>
                                <button type="button" class="arrow arrow-next">
                                    <img src="/img/slider-arrow-right.png" alt="">
                                </button>
                            </div>
                            <div class="small-slider-wrap">

                                <div class="small-slider" id="small-slider-{{$k}}">
                                    @if($gallery->image != null && $gallery->images() != null)
                                        @foreach($gallery->images() as $image)
                                            <div class="slide-item"><img src="{{ $image["img"] ?? '#' }}" alt=""></div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                        @php $k++; @endphp
                    @endforeach
                    <div class="right">
                            @if(isset($color[0]))
                                <p class="color-info">@lang('products.color'): <span id="color-text">{{$color[0]->colorData->{'name_'.app()->getLocale()} ?? '' }} </span></p>
                            @endif
                            <div class="colors-slider-wrap"   >
                            <div class="slider-navigation">
                                <button type="button" class="slick-next" id="color-slick-next"><img src="/img/arr-right.svg" alt=""></button>
                                <button type="button" class="slick-prev" id="color-slick-prev"><img src="/img/arr-right.svg" alt=""></button>
                            </div>
                            <div class="colors-slider">
                                @php $colorIndex = 0; @endphp
                                @foreach($color as $colorItem)
                                    <div class="item color-data-active" data-colorId="{{$colorItem->colorData->id}}" data-index="{{$colorIndex}}" data-color="{{$colorItem->colorData->{'name_'.app()->getLocale() } }}">
                                        @if($colorItem->image != null && $colorItem->images() != null)
                                            @foreach($colorItem->images() as $image)
                                                <div class="slide-item"><img src="{{ $image["img"] ?? '#' }}" alt=""></div>
                                                @php break; @endphp
                                            @endforeach
                                        @endif
                                    </div>
                                    @php $colorIndex++; @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="size-table">
                            <p>@lang('products.tableSize')</p>
                            <div class="variants-radios">

                                @php $index = 1; @endphp
                                @foreach($size as $itemSize)
                                    @if($itemSize->sizeData->{'name_'.app()->getLocale()} != '')
                                        <div @if($itemSize->sizeData->exist) class="radio-wrap" @else class="radio-wrap not-active" @endif  data-size="{{$itemSize->sizeData->id}}">
                                            <input @if(!$itemSize->sizeData->exist) disabled="disabled" @endif type="radio" value="{{$itemSize->sizeData->{'name_'.app()->getLocale() } }}" id="radio{{$index}}" name="size">
                                            <label for="radio{{$index}}">
                                                <b>{{$itemSize->sizeData->{'name_'.app()->getLocale() } }}</b>
                                            </label>
                                        </div>
                                    @endif
                                    @php $index++; @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="price-wrap">
                            <div style="display: flex; align-items: center;">
                                <span id="new-price" class="new-price price-product" data-price="{{ round($record->new_price,2) }}">{{ number_format($record->new_price, 0, ".", " ") }} {{$turkeyCurrency->symbol ?? '₺l'}}</span>
                                @if($i->sale > 0)
                                    <span class="old-price" style="text-decoration: line-through; margin-left: 10px" data-old-price="{{ $record->price }}">{{ $record->price }}</span>
                                @endif
                            </div>

                        </div>
                        <div class="buttons">
                            <div class="green-wrap">
                                <a class="green-btn add-to-cart-many" data-id="{{ $record->id }}">@lang('products.addToCart')</a>
                                <div class="product-count">
                                    <input type="text" class="input-number-item"  id="input-number-item" value="1" min="1">
                                    <div class="btns">
                                        <button type="button" class="btn-minus-item"><img src="/img/arr-right.svg" alt="">
                                        </button>
                                        <button type="button" class="btn-plus-item"><img src="/img/arr-right.svg" alt="">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <a  class="black-btn add-to-cart-many go-now" data-id="{{ $record->id }}">@lang('products.buyNow')</a>
                        </div>
                    </div>
                </div>

            <div class="about-product">
                <h3>@lang('products.about')</h3>
                <div class="about-product-info">
                    <div class="about-product-info-list">
                        @foreach($characteristic as $item)
                            <div class="list-item">
                                <p>{{$item->category->{'name_'.app()->getLocale()} }}<span class="dashed"></span></p>
                                <span class="text">{{ $item->{'name_'.app()->getLocale()} }}</span>
                            </div>
                        @endforeach


                    </div>
                    <div class="compound" style="max-width: 384px;
width: 100%;
display: flex;
flex-wrap: wrap;">
                        <div class="top" style="max-width: 384px;
width: 100%;
display: flex;
flex-wrap: wrap;">
                            <span>@lang('products.squad'):</span>
                            @foreach($record->compound as $items)
                                <span style="display: flex;width: 100%" class="text">{{ $items->compound->{'name_'.app()->getLocale()}  ?? ''}}  {{ $items->percent . '%' }}</span>
                            @endforeach

                        </div>
                        <div class="top" style="max-width: 384px;width: 100%;display: flex;flex-wrap: wrap;">
                            <span>@lang('merchant.form.body'):</span>
                            <span class="text" style="font-size: 13px;font-weight: 500;line-height: 18px; width: 100%"> {{$record->{'body_'.app()->getLocale()} }} </span>
                        </div>

                    </div>


                    @if($company != [])
                        <div class="about-seller">
                        <div class="seller-wrap">
                            <div class="seller-wrap-information">
                                <h4>{{$company->shop_name}}</h4>
                                <div class="hover">
                                    <img class="info-img" src="/img/info.svg" alt="">
                                    <div class="card-info">
                                        <p class="company_name">{{$company->shop_name}}</p>
                                        <p class="ogrn">IBAN :  {{$company->iban}}</p>
                                        <p class="ogrn">vkn :  {{$company->vkn}}</p>
                                        <p class="ogrn">tckn :  {{$company->tckn}}</p>
                                        <p class="ogrn">{{$company->phone}}</p>
                                    </div>
                                </div>
                            </div>




                            <div class="seller-review">
                                <div class="rate">
                                    <img src="/img/star-green.svg" alt="">
                                    <span>{{number_format($avgCompanyFeedback, 2, '.', ',')}}</span>
                                </div>
                                <!--<p class="review-link">{{$countCompanyFeedback}} @lang('system.f16')</p>-->

                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="review-detail">
                <div class="top-line">
                    <b>@lang('system.f10')</b>
                    <span class="number">{{$count_feedback}}</span>
                    @if($canAddNewFeedback == true)
                        <a href="#feedback-popup" class="add popup">@lang('system.add')</a>
                    @endif

                </div>
                <div class="review-detail-info">
                    <div class="review-detail-items">
                        @foreach($feedback as $item)
                            <div class="review-detail-item">
                            <div class="left-info">
                                <div class="img-wrap"><img src="/img/user-icon-dark.svg" alt=""></div>
                                <div class="texts">
                                    <div class="top-info">
                                        @if($item->user->role == 'user')
                                            <span>{{$item->user->name}} {{$item->user->s_name}}</span>
                                        @else
                                            <span>{{$item->user->company->company_name}} / {{$item->user->company->shop_name}}</span>
                                        @endif

                                        <span class="gray">{{$item->created_at}}</span>
                                    </div>
                                    <p>{{$item->text}}</p>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                    <div class="review-rating">
                        <span>@lang('products.rating'):</span>
                        <div class="review-rating-wrap">
                            <div class="rating-wrap">
                                <div class="stars" data-stars="{{number_format($rating, 2, '.', ',')}}">
                                    <svg height="25" width="23" class="star rating" data-rating="1">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="2">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="3">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="4">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                    <svg height="25" width="23" class="star rating" data-rating="5">
                                        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="number">{{number_format($rating, 2, '.', ',')}}</span>

                        </div>
                        <p>@lang('products.forFeedback') {{$count_feedback}} @lang('products.feedbacks')</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="feedback-popup" class="mfp-hide white-popup mfp-with-anim main-popup">
            <div class="popup-body">
                <button title="Close (Esc)" type="button" class="mfp-close"></button>
                <div class="feedback-popup-wrap">
                    <h2>@lang('system.y2') </h2>
                    <p class="gray-feedback">{{$i->id}}</p>
                    <div class="form-ajax-feedback">
                        <form class="main-form4" method="POST" action="{{ route("feedback.create",$i->id) }}">
                            @csrf
                            @if(Auth::check())
                            @if(Auth::user()->role == 'user')
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="rating" value="1" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="rating" value="2" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="rating" value="3" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="rating" value="4" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="rating" value="5" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            @endif
                            @endif
                            <textarea placeholder="@lang('system.y4')" name="text" cols="5">

                        </textarea>

                            <input type="submit" class="btn green-btn" value="@lang('system.y3') ">
                        </form>

                        <!--<p class="seller-data">@lang('system.y5')</p>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="top-products-slider-wrap detail-top-products">
        <div class="wrapper">
            <div class="title-wrap">
                <h2 class="main-title">@lang('system.YouSee') <a href="#">@lang('system.MaybeFavorite')</a></h2>
                <div class="slider-navigation">
                    <button type="button" class="slick-next"><img src="/img/arrow-right-small.svg" alt=""></button>
                    <button type="button" class="slick-prev"><img src="/img/arrow-left-small.svg" alt=""></button>
                </div>
            </div>
            <div class="top-products-slider">
                @foreach($checkProducts as $item)
                    @if($item->product->image() != null)
                        <a href="{{route('shop.item',['id' => $item->product_id])}}" class="item">
                        <span class="img-wrap"><img src="{{$item->product->image() ?? '#'}}" alt="">
                            @if($item->product->sale > 0)
                                <b>- {{$item->product->sale}} %</b>
                            @endif
                        </span>
                            <p>
                                <span class="new price-product"  data-price="{{ number_format($item->product->new_price, 0, ".", " ") }}">{{ number_format($item->product->new_price, 0, ".", " ") }} </span>
                                @if($i->sale > 0)
                                    <span class="old-price" data-old-price="{{ $item->product->price }}">{{ $item->product->price }}</span>
                                @endif
                            </p>
                            <span class="text">{{$item->product->{'name_'.app()->getLocale()} }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="style-wrap">
        <p>@lang('index.bottomText')</p>
    </div>



@endsection
