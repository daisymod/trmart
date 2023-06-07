@extends("body")
@section("title", "Реквизиты")
@section("content")
    <div class="requisites-page">
        <div class="wrapper">
            <ul class="breadcrumbs-list">
                <li><a href="/">@lang('footer.Index')</a></li>
                <li><a href="/requisites">@lang('footer.requisites')</a></li>
            </ul>
            <div class="top">
                <h2>
                    @lang('footer.requisites') Turkiye Mart
                </h2>
            </div>

            <div class="requisites-page__info">
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">@lang('system.k1')</p>
                    <p class="requisites-page__info__row__text">«Global Contract Service Sanal mağazacılık» Limited Şirketi</p>
                </div>
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">@lang('system.k2')</p>
                    <p class="requisites-page__info__row__text">Трумов Айбек Маратович</p>
                </div>
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">@lang('system.k3')</p>
                    <p class="requisites-page__info__row__text">Merdivenköy Mah., Dikyol Sk. B-Blok, No:2D:179  Kadıköy /Istanbul/Türkıye</p>
                </div>
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">@lang('system.k4') @lang('system.k5')</p>
                    <p class="requisites-page__info__row__text">3961437739</p>
                </div>
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">IBAN USD:</p>
                    <p class="requisites-page__info__row__text">TR48 0006 2000 7850 0009 0795 94</p>
                </div>
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">IBAN TL:</p>
                    <p class="requisites-page__info__row__text">TR40 0006 2000 7850 0006 2965 17 Turkiye Garanti Bankası A.Ş., SWIFT: TGBATRISXXX</p>
                </div>

                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">@lang('system.k6')</p>
                    <p class="requisites-page__info__row__text">admin@turkiyemart.com, sales@turkiyemart.com, info@turkiyemart.com</p>
                </div>
                <div class="requisites-page__info__row">
                    <p class="requisites-page__info__row__text">@lang('system.k7')</p>
                    <p class="requisites-page__info__row__text">+90 536 239 92 67</p>
                </div>
                <hr class="requisites-page__info__hr">
            </div>

            <div class="requisites-page__warning">
                <img class="requisites-page__warning__img" src="/img/warning.png">
                <p class="requisites-page__info__row__text">@lang('system.f7') <a class="requisites-page__info__row__text__url" href="/user/lk">@lang('system.lk').</a></p>
            </div>
        </div>
    </div>

    </div>

    <div class="style-wrap">
        <p>@lang('index.bottomText')</p>
    </div>
@endsection
