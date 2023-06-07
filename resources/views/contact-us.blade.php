@extends("body")
@section("title", "Контакты")
@section("content")
    <div class="contact-page">
        <div class="wrapper">
            <ul class="breadcrumbs-list">
                <li><a href="/">@lang('footer.Index')</a></li>
                <li><a href="/contact-us">@lang('system.k12')</a></li>
            </ul>
            <div class="top">
                <h2>
                    @lang('system.k12')
                </h2>
            </div>

            <div class="contact-page__card-row">
                <div class="contact-page__card-row__big_card" style="background-image:url({{url('img/Vector.svg')}});background-repeat: no-repeat;background-position: right 0px;">
                    <h2>@lang('system.k13')</h2>
                    <p class="contact-page__card-row__big_card__description_text">@lang('system.k131')</p>

                    <a href="#contact-popup" class="contact-page__card-row__big_card__modal-button green-btn popup">sales@turkiyemart.com</a>
                </div>

                <div class="contact-page__card-row__small_card">
                    <p class="contact-page__card-row__small_card__header_text">@lang('system.k20')</p>
                    <p class="contact-page__card-row__small_card__description_text">@lang('system.k21')</p>
                    <p href="mailto:sales@turkiyemart.com" class="contact-page__card-row__small_card__url_text">sales@turkiyemart.com</p>
                </div>

                <div class="contact-page__card-row__small_card">
                    <p class="contact-page__card-row__small_card__header_text">@lang('system.k22')</p>
                    <p class="contact-page__card-row__small_card__description_text">@lang('system.k23')</p>
                    <a class="contact-page__card-row__small_card__link_text">@lang('system.k24')</a>
                    <a href="mailto:admin@turkiyemart.com" class="contact-page__card-row__small_card__url_text">admin@turkiyemart.com</a>
                </div>
            </div>

            <div class="contact-page__card-row">
                <div class="contact-page__card-row__small_card">
                    <p class="contact-page__card-row__small_card__header_text">@lang('footer.partners')</p>
                    <p class="contact-page__card-row__small_card__description_text">@lang('system.k25')</p>
                    <a href="#" class="contact-page__card-row__small_card__url_text">@lang('system.k16')</a>
                </div>

                <div class="contact-page__card-row__small_card">
                    <p class="contact-page__card-row__small_card__header_text">@lang('system.k17')</p>
                    <p class="contact-page__card-row__small_card__description_text">@lang('system.k18')</p>
                    <a href="mailto:info@turkiyemart.com" class="contact-page__card-row__small_card__url_text">info@turkiyemart.com</a>
                </div>

                <div class="contact-page__card-row__big_card">
                    <p class="contact-page__card-row__big_card__header_text__address">@lang('system.k19')</p>

                    <p class="contact-page__card-row__big_card__address">Merdivenköy Mah., Dikyol Sk. B-Blok, No:2D:179  Kadıköy /Istanbul/Türkıye</p>
                </div>
            </div>
        </div>

    </div>
    <div id="contact-popup" class="mfp-hide white-popup mfp-with-anim main-popup">
        <div class="popup-body" style="width: 400px; margin-left: 33% ">
            <button title="Close (Esc)" type="button" class="mfp-close"></button>
            <div class="entrance-popup-wrap">
               <div class="tab-content-item active form-ajax">
                        <form class="main-form2" method="POST" action="{{ route("sendMessage") }}">
                            <div class="inputs-wrap">
                                <span>Номер телефона:</span>
                                <input type="tel" name="phone" required placeholder="">
                                <div class="error-field-text error-phone"></div>
                                <span>ФИО</span>
                                <input type="text" name="name" required placeholder="">
                                <span>Email</span>
                                <input type="text" name="email" required placeholder="">
                                <span>Сообщение</span>
                                <textarea type="text" name="message" required placeholder="">
                                </textarea>
                                <button class="green-btn" type="submit">Отправить</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <div class="style-wrap">
        <p>@lang('index.bottomText')</p>
    </div>
@endsection
