@extends("body")
@section("title", "О нас")
@section("content")
    <div class="about-page">
        <div class="wrapper">
            <ul class="breadcrumbs-list">
                <li><a href="/">@lang('menu.index') @endlang</a></li>
                <li><a href="/about-us">@lang('footer.about-us') </a></li>
            </ul>
        <div class="about-page__blocks">
            <div class="about-page__blocks__big_card" style="background-image: url(http://localhost:8000/img/Vector.svg);
background-repeat: no-repeat;
background-position: right 0px bottom -30px;
background-size: 60%;">
                <img src="img/about-img.png" class="about-page__blocks__big_card__img">
                <div class="about-page__blocks__big_card__content">
                    <div style="width: 100%;">
                        <p class="about-page__blocks__big_card__content__header">@lang('system.aboutUs1')</p>
                    </div>
                    <div style="width: 100%;">
                        <p class="about-page__blocks__big_card__content__description">@lang('system.aboutUs2')</p>
                        <p class="about-page__blocks__big_card__content__description">@lang('system.aboutUs21')</p>
                        <p class="about-page__blocks__big_card__content__description">@lang('system.aboutUs22')</p>
                    </div>
                </div>
            </div>

            <div class="about-page__blocks__row">
                <div class="about-page__blocks__row__half">

                        <div class="about-page__blocks__row__half__small_block">
                            <p class="about-page__blocks__row__half__small_block__header_text">35 000 +</p>
                            <p class="about-page__blocks__row__half__small_block__description">@lang('system.k8') </p>
                        </div>
                        <div class="about-page__blocks__row__half__small_block">
                            <p class="about-page__blocks__row__half__small_block__header_text">720 000 +</p>
                            <p class="about-page__blocks__row__half__small_block__description">@lang('system.k9') </p>
                        </div>

                        <div class="about-page__blocks__row__half__small_block">
                            <p class="about-page__blocks__row__half__small_block__header_text">68 000 +</p>
                            <p class="about-page__blocks__row__half__small_block__description">@lang('system.k10') </p>
                        </div>
                        <div class="about-page__blocks__row__half__small_block">
                            <p class="about-page__blocks__row__half__small_block__header_text">91 000 +</p>
                            <p class="about-page__blocks__row__half__small_block__description">@lang('system.k11') </p>
                        </div>
                </div>
                <div class="about-page__blocks__row__half">
                    <p class="about-page__blocks__row__half__text">@lang('system.aboutUs3')</p>
                    <p class="about-page__blocks__row__half__text">@lang('system.aboutUs31')</p>
                    <p class="about-page__blocks__row__half__text">@lang('system.aboutUs32')</p>
                    <p class="about-page__blocks__row__half__text">@lang('system.aboutUs33')</p>
                    <p class="about-page__blocks__row__half__text">@lang('system.aboutUs34')</p>
                </div>
                </div>
            </div>
        </div>



        </div>
    </div>

    </div>

    <div class="style-wrap">
        <p>@lang('index.bottomText')</p>
    </div>
@endsection
