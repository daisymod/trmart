<!doctype html>
<html lang="{{Lang::locale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="generator" content="Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <title>@yield('title') — TurkiyeMart </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.21/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ mix("/css/app.css") }}" rel="stylesheet">
    <link href="{{ mix("/css/main.css") }}" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
</head>
<body>
<p id="country_id" style="display: none">
@switch(Lang::locale())
        @case('ru')
            1
            @break

        @case('tr')
            2
            @break

        @case('kz')
            3
            @break
@endswitch
</p>
<p id="city_id" style="display: none">{{$location['city_id']}}</p>
<p id="card_price_turkey" style="display: none">{{$cart["price"]}}</p>

@if(Auth::check())
    <p id="user_id" style="display: none">{{Auth::user()->id}}</p>

@endif

<header>
    <div class="wrapper">
        <div class="top-line">
            <div class="select-wrap">
                <span class="text-top">@lang('system.language')</span>
                <select id="language-data" class="language-select">

                        <option class="option-data" data-active="0" @if($lang == 'ru') selected @endif value="ru">RU
                        </option>
                        <option class="option-data" data-active="1" value="kz" @if($lang == 'kz') selected @endif>KZ
                        </option>
                        <option class="option-data" data-active="2" value="tr" @if($lang == 'tr') selected @endif>TR
                        </option>
                </select>
            </div>
            <div class="select-wrap">
                <span class="text-top">@lang('system.currency')</span>
                <select name="" id="country-list">

                </select>
            </div>

            <!--<div class="select-wrap">
                <span  id="symbol-data">

                </span>
            </div> -->
            @auth
                <a href="{{ route("user.lk") }}" class="marketplace popup">
                    <span class="circle"></span>
                    <span class="text">{{ __("menu.merchant.reg") }}</span>
                </a>
                <div class="buttons">
                    @if(Auth::user()->role =='user' || Auth::user()->role =='logist' )
                        <p>{{ Auth::user()->name }} {{ Auth::user()->s_name }} <br> <span>{{ Auth::user()->role }}</span></p>
                    @elseif(Auth::user()->role =='merchant')
                        <p>{{ \App\Models\Company::where('user_id','=',Auth::user()->id)->first()->company_name }}<br> <span>{{ Auth::user()->role }}</span></p>
                    @else
                        <p>Admin <br> <span>{{ Auth::user()->role }}</span></p>
                    @endif

                    <a href="{{ route("user.lk") }}" class="red-btn">{{ __("menu.lk") }}</a>
                    <a href="{{ route("user.exit") }}" class="green-btn sign-btn"><img src="/img/sign-up-icon.svg" alt=""></a>
                </div>
            @else
                <a href="#registration-popup" class="marketplace popup">
                    <span class="circle"></span>
                    <span class="text">{{ __("menu.merchant.reg") }}</span>
                </a>
                <div class="buttons">
                    <a href="#entrance-popup"  onclick="openPopUpRegister()"  id="UserRegister"  class="red-btn popup">{{ __("menu.user.reg") }}</a>
                    <a href="#entrance-popup" onclick="openPopUpLogin()" id="UserLogin" class="green-btn popup">{{ __("menu.user.login") }}</a>
                </div>
            @endauth
        </div>
        <div class="bottom-line">
            <a href="#" class="drop-menu">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </a>
            <a href="/" class="logo"><img src="/img/logo.svg" alt=""></a>
            <form class="search-form" action="{{ route("shop.find") }}">
                <input id="search-item" autocomplete="off" class="search-data" type="text" list="search-items-result"  name="find" placeholder="@lang('search.searchProduct')">
                <div style="display: none" id="search-result">

                </div>
                <button type="submit"><img src="/img/loop.svg" alt=""></button>
            </form>
            <div class="basket">
                <img src="/img/basket-icon.svg" alt="">
                <span  id="cart-price" class="cart-price"></span><span id="symbol-cart"> </span>
                <a href="{{ route("cart.index") }}">@lang('body.toCart')</a>
            </div>
            <div class="mobile-icons">
                @auth
                    <a href="{{ route("user.lk") }}"><img src="/img/user-icon.svg" alt=""></a>
                    <a href="{{ route("user.exit") }}" class="green-btn sign-btn"><img src="/img/sign-up-icon.svg" alt=""></a>
                @else
                    <a href="#entrance-popup" onclick="openPopUpLogin()" id="UserLogin" class="popup"><img src="/img/user-icon.svg" alt=""></a>
                @endauth

                <a href="#" class="search-menu"><img src="/img/search-icon.svg" alt=""></a>
                <a href="{{ route("cart.index") }}"><img src="/img/basket-icon-mobile.svg" alt=""></a>
            </div>
            <div class="search-menu-wrapper">
                <form class="search-menu-wrapper-form" action="{{ route("shop.find") }}">
                    <input id="search-item" autocomplete="off" class="form-control" type="text" list="search-items-result"  name="find" placeholder="@lang('search.searchProduct')">
                    <div style="display: none" id="search-result">

                    </div>
                    <button type="submit"><img src="/img/loop.svg" alt=""></button>
                </form>
            </div>
            <div class="menu-wrapper">
                <ul class="tab-menu">
                    @foreach($catalogMenu as $k=>$i)
                        <li @if($k==0) class="active" @endif><a>{{ $i->{"name_".app()->getLocale()} }}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($catalogMenu as $k=>$i)
                        <div class="tab-content-item @if($k==0) active @endif">
                            <div class="tab-menu-wrapper">
                                <div class="links">
                                    @foreach($i->child()->orderBy("name_".app()->getLocale())->get() as $child)
                                        @if(count($child->child) > 0)
                                            <div class="info-wrap">
                                                <a href="{{ route("shop.list", $child->id) }}"><h6>{{ $child->{"name_".app()->getLocale()} }}</h6></a>
                                                @foreach($child->child()->orderBy("name_".app()->getLocale())->get() as $c)
                                                    @if($c->is_active == 1)
                                                        <a href="{{ route("shop.list", $c->id) }}">{{ $c->{"name_".app()->getLocale()} }}</a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="info-wrap">
                                                <a href="{{ route("shop.list", $child->id) }}">{{ $child->{"name_".app()->getLocale()} }}</a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="img-wrap">
                                    <div class="img"><img src="/img/menu-img.jpg" alt=""></div>
                                    <b>@lang('system.f11') 1 300 @lang('system.f12')</b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</header>

@yield("content")

<footer>
    <div class="wrapper">
        <div class="footer-wrap">
            <div class="menu-wrap">
                <h3>@lang('footer.company')</h3>
                <a href="/about-us">@lang('footer.about-us')</a>
                <a href="/requisites">@lang('footer.requisites')</a>
                <a href="/contact-us">@lang('footer.contact-us')</a>
            </div>
            <div class="menu-wrap">
                <h3>@lang('footer.partners')</h3>
                <a class="popup" href="#registration-popup">@lang('footer.sell-market')</a>
                <a target="_blank" class="open-file-politic" href="/public_offer_merchant{{app()->getLocale()}}.pdf">@lang('footer.politic7')</a>
            </div>
            <div class="menu-wrap">
                <h3>@lang('footer.buyers')</h3>
                <a href="#">@lang('footer.question')</a>

                <a target="_blank" class="open-file-politic" href="/public_offer{{app()->getLocale()}}.pdf">@lang('footer.politic5')</a>
                <a target="_blank" class="open-file-politic" href="/return_product{{app()->getLocale()}}.pdf">@lang('footer.politic2')</a>
                <a target="_blank" class="open-file-politic" href="/delivery{{app()->getLocale()}}.pdf">@lang('footer.politic4')</a>
                <a target="_blank" class="open-file-politic" href="/policy{{app()->getLocale()}}.pdf">@lang('footer.politic')</a>
                <a target="_blank" class="open-file-politic" href="/rules_user{{app()->getLocale()}}.pdf">@lang('footer.politic3')</a>



            </div>
            <div class="info-wrap">
                <p class="copyright">@lang('footer.text-about-footer')</p>
                <a href="#">@lang('footer.all-rights')</a>
                <ul class="logos">
                    <li><a href="#"><img src="/img/logo1.svg" alt=""></a></li>
                    <li><a href="#"><img src="/img/logo2.svg" alt=""></a></li>
                    <li><a href="#"><img src="/images/iyzico.jpg" style="width:60px;" alt=""></a></li>
                </ul>
            </div>
        </div>
        <a href="/" class="footer-logo"><img src="/img/footer-logo.svg" alt=""></a>
    </div>
</footer>

<div id="entrance-popup" class="mfp-hide white-popup mfp-with-anim main-popup">
    <div class="popup-body">
        <button title="Close (Esc)" type="button" class="mfp-close"></button>
        <div class="entrance-popup-wrap">
            <div class="tab-menu">
                <li id="register-tab" class="active"><a href="#">@lang('body.login')</a></li>
                <li id="login-tab"><a href="#">@lang('body.register')</a></li>
            </div>
            <div class="tab-content">
                <div id="register-tab-content" class="tab-content-item active form-ajax">
                    <form class="main-form2" method="POST" action="{{ route("user.login") }}">
                        @csrf
                        <div class="inputs-wrap">
                            <span>@lang('body.phone')</span>
                            <div class="input-box">
                                <input type="text" name="phone" id="input-phone" required placeholder="Номер телефона:">
                                <div class="error-field-text error-phone"></div>
                            </div>
                            <span>@lang('body.password')</span>
                            <div class="input-box">
                                <input type="password" name="pass" required placeholder="@lang('body.password')">
                                <div class="error-field-text error-pass"></div>
                            </div>
                        </div>

                        <div  class="checkbox-wrap">
                            <a href="#reset-password" class="popup reset-password-text">@lang('body.resetPassword')</a>
                        </div>

                        <button  class="green-btn" id="login-button" type="submit">@lang('body.loginTo')</button>

                    </form>
                </div>
                <div id="login-tab-content" class="tab-content-item">
                    <div class="form-wrap form-ajax-reg-user">
                        <form class="main-form3" method="POST" action="{{ route("user.reg") }}">
                            @csrf
                            <div class="inputs-wrap">
                                <span>@lang('body.name')</span>
                                <div class="input-box">
                                    <input type="text" name="name" required placeholder="@lang('body.name')">
                                    <div class="error-field-text error-pass"></div>
                                </div>
                                <span>@lang('body.phone')</span>
                                <div class="input-box">
                                    <input type="text" id="input-phone-register-user" name="phone" required placeholder="@lang('body.phone')">
                                    <div class="error-field-text error-phone"></div>
                                </div>
                                <span>@lang('body.password')</span>
                                <div class="input-box">
                                    <input type="password" name="password" required placeholder="@lang('body.password')">
                                    <div class="error-field-text error-password"></div>
                                </div>
                                <span>@lang('body.passwordRepeat')</span>
                                <div class="input-box">
                                    <input type="password" name="password_confirmation" required placeholder="@lang('body.passwordRepeat')">
                                    <div class="error-field-text error-password_confirmation"></div>
                                </div>
                            </div>
                            <div class="checkbox-wrap">
                                <label>
                                    <input type="checkbox" onchange="acceptFormRegister()" id="accept-checkbox-register">
                                    <span></span>
                                    <p>@lang('body.accept1')
                                        <a  target="_blank" class="open-file-politic" href="/public_offer{{app()->getLocale()}}.pdf">@lang('footer.politic5')</a>
                                        ,
                                        <a target="_blank" class="open-file-politic" href="/policy{{app()->getLocale()}}.pdf">@lang('footer.politic')</a>
                                        ,
                                        <a target="_blank" class="open-file-politic" href="/rules_user{{app()->getLocale()}}.pdf">@lang('footer.politic3')</a>

                                    </p>
                                </label>
                            </div>
                            <button class="green-btn" disabled id="register-button" type="submit">@lang('body.sendCode')</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="enter-code-wrap enter-code-wrap-user">
            <a href="javascript:history.back()" class="back"><img src="/img/back-icon.png" alt=""></a>
            <h3>@lang('body.writeCode')</h3>
            <div class="form-ajax">
                <form action="{{ route("user.reg.sms") }}">
                    @csrf
                    <p class="top"@lang('body.CodePhone') <span></span></p>
                    <input name="sms" required type="text" placeholder="• • • • • •">
                    <div class="error-field-text error-sms"></div>
                    {{--<p class="again-in">Запросить код повторно через <span class="red">0:33</span></p>--}}
                    {{--<p class="green">Запросить код повторно</p>--}}
                    {{--<p class="red">Вы ввели неверный код!</p>--}}
                    <button class="green-btn" type="submit">@lang('body.loginTo')</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="reset-password" class="mfp-hide white-popup mfp-with-anim main-popup">
    <div class="popup-body">
        <button title="Close (Esc)" type="button" class="mfp-close"></button>
        <div class="entrance-popup-wrap">
            <h3>@lang('body.resetPasswordText')</h3>
            <div class="form-wrap form-ajax-reg-user">
               <form class="main-form3" method="POST" action="{{ route("user.checkPhone") }}">
                   @csrf
                   <div class="inputs-wrap">
                       <span>@lang('body.phone')</span>
                       <div class="input-box">
                           <input style="padding-left: 45px !important;" type="text" name="phone" id="input-phone-reset-data" required placeholder="@lang('body.phone')">
                           <div class="error-field-text error-phone"></div>
                       </div>
                   </div>
                   <button class="green-btn mt-4" type="submit">@lang('body.sendCode')</button>
               </form>
            </div>
        </div>
        <div class="enter-code-wrap enter-code-wrap-user">
            <h3>@lang('body.writeCode')</h3>
            <div class="form-ajax form-ajax-sms-user">
                <form action="{{ route("user.checkSMS") }}">
                    @csrf
                    <p class="top"@lang('body.CodePhone') <span></span></p>
                    <input name="sms" required type="text" placeholder="• • • • • •">
                    <div class="error-field-text error-sms"></div>
                    <div class="checkbox-wrap">
                    </div>
                    <button class="green-btn" type="submit">@lang('body.loginTo')</button>
                </form>
            </div>
        </div>


        <div class="enter-code-wrap enter-code-wrap-user-password">
            <h3>@lang('body.resetPasswordText')</h3>
            <div class="form-wrap form-ajax-sms-user-reset">
                <form action="{{ route("user.reset-password") }}">
                    @csrf
                    <input type="hidden" name="phone" id="phone-data-for-reset" value="">
                    <div class="inputs-wrap">
                        <span>@lang('body.password')</span>
                        <div class="input-box">
                            <input type="password" name="password" required placeholder="@lang('body.password')">
                            <div class="error-field-text error-password"></div>
                        </div>
                        <span>@lang('body.passwordRepeat')</span>
                        <div class="input-box">
                            <input type="password" name="password_confirmation" required placeholder="@lang('body.passwordRepeat')">
                            <div class="error-field-text error-password_confirmation"></div>
                        </div>
                    </div>
                    <button class="green-btn"  type="submit">@lang('body.loginTo')</button>
                </form>


            </div>
        </div>
    </div>
</div>

<div id="registration-popup" class="mfp-hide white-popup mfp-with-anim main-popup">
    <div class="popup-body">
        <button title="Close (Esc)" type="button" class="mfp-close"></button>
        <div class="registration-popup-wrap">
            <h2>@lang('body.register') </h2>
            <div class="form-ajax-reg-merchant">
                <form class="main-form4" method="POST" action="{{ route("merchant.reg") }}">
                    @csrf
                    <div class="inputs-wrap">
                        <span>@lang('body.phone')</span>
                        <input type="text"  id="input-phone-register" name="phone" required placeholder="">
                        <div class="error-field-text error-phone"></div>
                        <span>@lang('body.first_name')</span>
                        <input type="text" name="first_name" required placeholder="">
                        <span>@lang('body.last_name')</span>
                        <input type="text" name="last_name" required placeholder="">
                        <span>@lang('body.patronymic') <p class="no-required">@lang('system.qq2')</p></span>
                        <input type="text" name="patronymic" required placeholder="">
                        <div class="error-field-text error-name"></div>
                        <span>@lang('body.email')</span>
                        <input type="email" name="email" required placeholder="">
                        <span>@lang('body.companyName')</span>
                        <input type="text" name="company_name" required placeholder="">
                        <span>@lang('body.password')</span>
                        <input type="password" name="password" required placeholder="">
                        <span>@lang('body.passwordRepeat')</span>
                        <input type="password" name="password_confirmation" required placeholder="">
                    </div>
                    <div class="checkbox-wrap">
                        <label>
                            <input type="checkbox" onchange="acceptFormRegisterMerchant()" id="accept-checkbox-register-merchant">
                            <span></span>
                            <p>@lang('body.accept1')
                                <a  target="_blank" class="open-file-politic" href="/public_offer_merchant{{app()->getLocale()}}.pdf">@lang('footer.politic7')</a>,
                                <a target="_blank" class="open-file-politic" href="/policy{{app()->getLocale()}}.pdf">@lang('footer.politic')</a>,
                                <a target="_blank" class="open-file-politic" href="/rules_user{{app()->getLocale()}}.pdf">@lang('footer.politic3')</a>
                            </p>
                        </label>
                    </div>
                    <button class="green-btn" disabled id="register-merchant-button" type="submit">@lang('body.register')</button>

                </form>
            </div>
        </div>
        <div class="enter-code-wrap enter-code-wrap-merchant">
            <h3>@lang('body.writeCode')</h3>
            <div class="form-ajax">
                <form action="{{ route("merchant.reg.sms") }}">
                    @csrf
                    <p class="top"@lang('body.CodePhone') <span></span></p>
                    <input name="first_name" id="first_name_merchant"  type="hidden" >
                    <input name="last_name" id="last_name_merchant"  type="hidden" >
                    <input name="patronymic" id="patronymic_merchant"  type="hidden" >
                    <input name="email" id="email_merchant"  type="hidden" >
                    <input name="company" id="company_name_merchant"  type="hidden" >
                    <input name="password" id="password_merchant"  type="hidden" >
                    <input name="phone" id="phone_merchant"  type="hidden" >

                    <input name="sms" required type="text" placeholder="• • • • • •">
                    <div class="error-field-text error-sms"></div>
                    <button class="green-btn" type="submit">@lang('body.loginTo')</button>

                </form>
            </div>
        </div>
    </div>
</div>


<script src="{{ mix("/js/app.js") }}"></script>

</body>
</html>


<script>
    function openPopUpRegister(){

        document.getElementById('register-tab').classList.remove('active');
        document.getElementById('login-tab').classList.add('active');


        document.getElementById('register-tab-content').classList.remove('active');
        document.getElementById('login-tab-content').classList.add('active');
    }


    function openPopUpLogin(){

        document.getElementById('register-tab').classList.add('active');
        document.getElementById('login-tab').classList.remove('active');

        document.getElementById('register-tab-content').classList.add('active');
        document.getElementById('login-tab-content').classList.remove('active');

    }


    function acceptForm(){

    }


    function acceptFormRegister(){
        if (document.getElementById('accept-checkbox-register').checked){
            document.getElementById('register-button').disabled = false;
        }else{
            document.getElementById('register-button').disabled = true;
        }
    }

    function acceptFormRegisterMerchant(){
        if (document.getElementById('accept-checkbox-register-merchant').checked){
            document.getElementById('register-merchant-button').disabled = false;
        }else{
            document.getElementById('register-merchant-button').disabled = true;
        }
    }

</script>
