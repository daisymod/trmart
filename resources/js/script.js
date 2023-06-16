import intlTelInput from "intl-tel-input";

$(document).ready(function () {

    jQuery('.orders_status').each(function(index , element) {
        var currentElement = $(this);
        var valueData = currentElement.attr('data-status');
        currentElement.val(valueData);
    });

    jQuery('.radio1').init(function() {
        if(document.getElementById("radio1") != null) {
            document.getElementById("radio1").checked = true;
        }
    });


    /*
        window.addEventListener('click', function(event){
        var popover = document.getElementById('language-data');

        var lang = document.getElementById('language-data-top');
        var lang1 = document.getElementById('language-data-top1');
        var lang2 = document.getElementById('language-data-top2');

        var array = [lang,lang1,lang2]
        if(array.includes(event.target)) {
            document.getElementById('select-items-language').style.display = 'block';
        }

        if(!array.includes(event.target)){
            document.getElementById('select-items-language').style.display = 'none';
        }
    })
    */
    if (window.location.pathname == '/merchant/self'){
        if (document.getElementById('equal_addresses') != undefined) {
            jQuery('#equal_addresses').init(function () {
                if (document.getElementById('equal_addresses').dataset.check == 1) {
                    var city = jQuery('select[name=city]');
                    var street = jQuery('input[name=street]');
                    var number = jQuery('input[name=number]');
                    var office = jQuery('input[name=office]');

                    city.prop('readonly', true);
                    street.prop('readonly', true);
                    number.prop('readonly', true);
                    office.prop('readonly', true);
                }
            });

            jQuery('#equal_addresses').change(function() {
            var city = jQuery('select[name=city]');
            var street = jQuery('input[name=street]');
            var number = jQuery('input[name=number]');
            var office = jQuery('input[name=office]');

            var legal_city = jQuery('select[name=legal_address_city]');
            var legal_street = jQuery('input[name=legal_address_street]');
            var legal_number = jQuery('input[name=legal_address_number]');
            var legal_office = jQuery('input[name=legal_address_office]');

           if (document.getElementById('equal_addresses').dataset.check == 0){
               $("select[name=city] option:selected").each(function () {
                   $(this).prop('selected', false);
               });
               city.find('option[value="'+legal_city.find(":selected").val()+'"]').prop('selected', true);
               street.val(legal_street.val());
               number.val(legal_number.val());
               office.val(legal_office.val());

               city.prop('readonly', true);
               street.prop('readonly', true);
               number.prop('readonly', true);
               office.prop('readonly', true);

               document.getElementById('equal_addresses').dataset.check = 1;
           }else{
               city.prop('readonly', false);
               street.prop('readonly', false);
               number.prop('readonly', false);
               office.prop('readonly', false);

               city.find('option:first').prop('selected', true);
               street.val("");
               number.val("");
               office.val("");
               document.getElementById('equal_addresses').dataset.check = 0;
           }


        });
        }

        if (document.getElementById('vkn') != undefined) {
            var vkn = document.getElementsByName('vkn');
            new IMask(vkn[0], {
                mask: "00000000000",
            });
        }

        if (document.getElementById('tckn') != undefined) {
            var tckn = document.getElementsByName('tckn');
            new IMask(tckn[0], {
                mask: "00000000000",
            });
        }

        if (document.getElementById('iban') != undefined) {
            var iban = document.getElementsByName('iban');
            new IMask(iban[0], {
                mask: "TR 00 - 0000 - 0000 - 0000 - 0000 - 0000 - 00",
            });
        }
    }

    jQuery('.btn-minus-item').click(function() {
        var input  = $('#input-number-item');
        if (input.val() == 0){
            input.val(0);
        }else{
            input.val(parseInt(input.val()) - 1);
        }
    });


    jQuery('.btn-plus-item').click(function() {
        var input  = $('#input-number-item');
        input.val(parseInt(input.val()) + 1);
    });

    jQuery('#language-data').change(function() {
        $.ajax({
            type: "GET",
            url: "/lang/" + this.value,
            cache: false,
            success: function(data){
                location.reload();
            }
        });
    });



    jQuery('.id-checkbox').change(function() {
        let checkboxItems = document.getElementsByClassName('id-checkbox');
        let index = 0;

        for (let i = 0; i <  checkboxItems.length; i++){
            if (document.getElementsByClassName('id-checkbox')[i].checked){
                index++;
            }
        }
        if (index >0){
            document.getElementById('button-action-catalog_item').disabled = false;
        }else{
            document.getElementById('button-action-catalog_item').disabled = true;
        }

    });

    jQuery('#show-more-product').init(function() {
        localStorage.setItem('page_product','1');
    });


    jQuery('#pagination-list').change(function() {
        window.location.href = location.protocol + '//' + location.host + location.pathname + '?limit='+this.value;
    });


    jQuery('#from-data').change(function() {
        let element = document.getElementById('from-data-export');
        console.log(this.value);
        element.value = this.value;
    });

    jQuery('#to-data').change(function() {
        let element = document.getElementById('to-data-export');
        element.value = this.value;
    });

    jQuery('#merchant').change(function() {
        let element = document.getElementById('merchant-export');
        element.value = this.value;
    });

    jQuery('#orders_status').change(function() {
        let element = document.getElementById('orders_status-export');
        element.value = this.value;
    });


    jQuery('#show-more-product').click(function() {
        let page = parseInt(localStorage.getItem('page_product')) + 1 ?? 2;
        $.ajax({
            url: "/api/product/paginate",
            type: "GET",
            dataType: 'json',
            data: {
                page: page
            },
            success: function (data) {
                data.items.data.forEach(element =>
                    {
                        if (typeof element.id == 'number'){
                            let name_product = ''
                            switch (document.documentElement.lang){
                                case 'ru':
                                    name_product = element.name_ru
                                    break;
                                case 'kz':
                                    name_product = element.name_kz
                                    break;
                                case 'tr':
                                    name_product = element.name_tr
                                    break;
                            }

                            let img = JSON.parse(element.image);
                            if (img[0].file == ''){
                                return;
                            }
                            let html = "<a href=\"https://turkiyemart.com/shop/"+element.id+".html\" class=\"sale-item\">\n" +
                                "                    <span class=\"img-wrap\"><img src="+img[0].file+" alt=\"\"></span>\n" +
                                "                    <p>\n" +
                                "                        <span class=\"new price-product\" data-price="+element.price+">"+getCountPrice(element.price )+ ' ' + localStorage.getItem('symbol') +"</span>\n" +
                                "                                            </p>\n" +
                                "                    <span class=\"text\">"+ name_product +"</span>\n" +
                                "                </a>"
                            document.getElementsByClassName("sale-items")[0].insertAdjacentHTML( 'beforeend', html )
                        }



                    }
                );

                localStorage.setItem('page_product',data.items.current_page);
            }
        });
    })


    function getCountPrice(data) {
        var price = data;
        var result  = parseFloat(price).toFixed(2) * parseFloat(localStorage.getItem('coefficient')).toFixed(2);

        return result.toFixed(2);
    }

    jQuery('#input-phone-reset-data').init(function() {


        let input = document.querySelector("#input-phone-reset-data");
        let data = intlTelInput(input, {
            initialCountry: "auto",
            autoPlaceholder: "polite",
            customPlaceholder: null,
            onlyCountries: ['tr','kz','uz','kg'],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            geoIpLookup: function (callback) {
                var countryCode = 'kz';
                callback(countryCode);
            }
        });

        let mask = new IMask(input, {
            mask: "+{7}(000)000-00-00",
        });

        jQuery(input).on("countrychange", function () {
            switch(data.defaultCountry) {
                case 'kz':
                    mask.updateOptions({
                        mask: "+{7}(000)000-00-00",
                    });
                    break
                case 'uz':
                    mask.updateOptions({
                        mask: "+{998}(00)000-00-00",
                    });
                    break

                case 'tr':
                    mask.updateOptions({
                        mask: "+{90}(000)000-00-00",
                    });
                    break
                case 'kg':
                    mask.updateOptions({
                        mask: "+{996}(000)000000",
                    });
                    break

            }
        });
    });


    jQuery('#input-phone').init(function() {


        let input = document.querySelector("#input-phone");
        let data = intlTelInput(input, {
            initialCountry: "auto",
            autoPlaceholder: "polite",
            customPlaceholder: null,
            onlyCountries: ['tr','kz','uz','kg'],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            geoIpLookup: function (callback) {
                var countryCode = 'kz';
                callback(countryCode);
            }
        });

        let mask = new IMask(input, {
            mask: "+{7}(000)000-00-00",
        });

        jQuery(input).on("countrychange", function () {
            switch(data.defaultCountry) {
                case 'kz':
                    mask.updateOptions({
                        mask: "+{7}(000)000-00-00",
                    });
                    break
                case 'uz':
                    mask.updateOptions({
                        mask: "+{998}(00)000-00-00",
                    });
                    break

                case 'tr':
                    mask.updateOptions({
                        mask: "+{90}(000)000-00-00",
                    });
                    break
                case 'kg':
                    mask.updateOptions({
                        mask: "+{996}(000)000000",
                    });
                    break

            }
        });
    });

    jQuery('#input-phone-register-user').init(function() {
        let input = document.querySelector("#input-phone-register-user");
        let data =  intlTelInput(input, {
            initialCountry: "auto",
            autoPlaceholder: "polite",
            onlyCountries: ['kz','uz','kg'],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            geoIpLookup: function (callback) {
                var countryCode = 'kz';
                callback(countryCode);
            },
            customPlaceholder:function(selectedCountryPlaceholder,selectedCountryData){
                console.log(selectedCountryData);
                return '+'+selectedCountryData.dialCode+' '+selectedCountryPlaceholder.replace(/[0-9]/g,'_');
            },
        });

        let mask = new IMask(input, {
            mask: "+{7}(000)000-00-00",
        });

        jQuery(input).on("countrychange", function () {
            switch(data.defaultCountry) {
                case 'kz':
                    mask.updateOptions({
                        mask: "+{7}(000)000-00-00",
                    });
                    break
                case 'uz':
                    mask.updateOptions({
                        mask: "+{998}(00)000-00-00",
                    });
                    break

                case 'tr':
                    mask.updateOptions({
                        mask: "+{90}(000)000-00-00",
                    });
                    break
                case 'kg':
                    mask.updateOptions({
                        mask: "+{996}(000)000000",
                    });
                    break

            }
        });

    });


    jQuery('#input-phone-register').init(function() {
        let input = document.querySelector("#input-phone-register");
        let data =  intlTelInput(input, {
            initialCountry: "auto",
            autoPlaceholder: "polite",
            customPlaceholder: null,
            onlyCountries: ['tr','kz'],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            geoIpLookup: function (callback) {

                var countryCode = 'kz';
                callback(countryCode);
            }
        });

        let mask = new IMask(input, {
            mask: "+{7}(000)000-00-00",
        });

        jQuery(input).on("countrychange", function () {
            switch(data.defaultCountry) {
                case 'kz':
                    mask.updateOptions({
                        mask: "+{7}(000)000-00-00",
                    });
                    break
                case 'uz':
                    mask.updateOptions({
                        mask: "+{998}(00)000-00-00",
                    });
                    break

                case 'tr':
                    mask.updateOptions({
                        mask: "+{90}(000)000-00-00",
                    });
                    break
                case 'kg':
                    mask.updateOptions({
                        mask: "+{996}(000)000000",
                    });
                    break

            }
        });
    });

    $('#city-profile-search').select2({

    });


    function renderAccountInformation(data, container) {
        if (data.id === '') {
            return 'Select account';
        }
        const el = data.element;
        const img = $(el).attr('data-img');
        const title = $(el).attr('data-title');
        return $(`<div class='option-wrap'><img src=${img} /><span>${title}</span></div>`);
    }

    $('#accountSelector').select2({
        allowClear: false,
        minimumResultsForSearch: -1,
        templateResult: renderAccountInformation,
        templateSelection: renderAccountInformation
    });

    $('.select-wrap select').select2({
        minimumResultsForSearch: 6,
    });

    $('#brand-item').select2({
        minimumResultsForSearch: 6,
        tags: true
    });


    $('#language-data').select2({
        minimumResultsForSearch: 6,
        tags: true,
        templateResult: function (idioma) {
            var $span = $("<span><img class='image-lang' src='/img/"+idioma.id + "-icon.png'/> " + idioma.text + "</span>");
            return $span;
        },
        templateSelection: function (idioma) {
            var $span = $("<span><img class='image-lang' src='/img/"+idioma.id + "-icon.png'/> " + idioma.text + "</span>");
            return $span;
        }
    });


    jQuery(".alert-success").init(function (){
        $('.alert-success').addClass('hidden-alert');
    })





    $('.home-slider').slick({
        dots: true,
        slidesToShow: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        prevArrow: $('.home-slider-wrap .slider-navigation .slick-prev'),
        nextArrow: $('.home-slider-wrap .slider-navigation .slick-next'),
    });


    $(".colors-slider").on("afterChange", function (){
        let color = document.getElementsByClassName('color-data-active slick-active');
        document.getElementById('color-text').innerHTML = color[0].dataset.color;
        $.ajax({
            type: "GET",
            url: "/api/product/color-size",
            data: {
                'item_id': window.location.pathname.split("/").pop(),
                'color': color[0].dataset.colorid,
                'lang': 'tr',
            },
            cache: false,
            success: function (data) {
                let radios = document.getElementsByClassName('variants-radios');
                radios[0].innerHTML = data;
            }
        });
    })


    $('.colors-slider').slick({
        dots: false,
        slidesToShow: 1,
        variableWidth: true,
        slidesToScroll: 1,
        arrows: true,
        infinite: false,
        prevArrow: $('.colors-slider-wrap .slider-navigation .slick-prev'),
        nextArrow: $('.colors-slider-wrap .slider-navigation .slick-next'),
        responsive: [
            {
                breakpoint: 1301,
                settings: {
                    slidesToShow: 4,
                    variableWidth: true,
                }
            },
            {
                breakpoint: 481,
                settings: {
                    slidesToShow: 3,
                }
            },
        ]

    });

    $('.top-products-slider').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        variableWidth: true,
        prevArrow: $('.top-products-slider-wrap .slider-navigation .slick-prev'),
        nextArrow: $('.top-products-slider-wrap .slider-navigation .slick-next'),
    });

    $('.brands-slider').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        variableWidth: true,
        prevArrow: $('.brands-slider-wrap .slider-navigation .slick-prev'),
        nextArrow: $('.brands-slider-wrap .slider-navigation .slick-next'),
    });

    $(".drop-menu").click(function (e) {
        e.stopPropagation();
        $(this).toggleClass("is-active");
        $(".menu-wrapper").toggleClass("open");
        $("body, html").toggleClass("overflow");
    });

    $(".search-menu").click(function (e) {
        e.stopPropagation();
        $(this).toggleClass("is-active");
        $(".search-menu-wrapper").toggleClass("open");
        $("body, html").toggleClass("overflow");
    });

    $(".add-item").on("click", function (e) {
        e.preventDefault();
        $(this).closest(".add-item-wrap").find(".add-item-content").toggleClass("open");
    })

    $(".add-item-child").on("click", function (e) {
        e.preventDefault();
        $(this).closest(".add-item-wrap").find(".add-item-content-child").toggleClass("open");
    })



    $(".main-form").validate({
        errorPlacement: function (error, element) {
        }
    });

    $(".main-form2").validate({
        errorPlacement: function (error, element) {
        }
    });

    $(".main-form3").validate({
        errorPlacement: function (error, element) {
        }
    });

    $(".main-form4").validate({
        errorPlacement: function (error, element) {
        }
    });
    $(".main-form5").validate({
        errorPlacement: function (error, element) {
        }
    });

    /*    $('.main-form3').on("submit", function (e) {
            e.preventDefault();
            if ($('.main-form3').valid()) {
                $(this).closest(".entrance-popup-wrap").hide();
                $(".enter-code-wrap").show();
            }
        });*/

    //	Upload one image
    $('#img-upload').on('change', function (e) {
        return readURL(this);
    });

    var readURL = function (input) {
        var reader;
        if (input.files && input.files[0]) {
            reader = new FileReader();
            reader.onload = function (e) {
                $('.img-upload-wrap .img').css('background-image', 'url(' + e.target.result + ')');
                $('.img-upload-wrap').addClass('uploaded');
                $('.img-upload-wrap .icon-change-wrap').removeClass("shown");

            };
            return reader.readAsDataURL(input.files[0]);
        }
    };

    $(document).on("mouseenter", '.img-upload-wrap.uploaded label', function () {
        console.log(1);
        $('.img-upload-wrap .icon-change-wrap').addClass("shown");
    });

    $(document).on("mouseleave", '.img-upload-wrap.uploaded label', function () {
        console.log(2);
        $('.img-upload-wrap .icon-change-wrap').removeClass("shown");
    });

    $(".img-upload-wrap .delete-img").on("click", function () {
        $('.img-upload-wrap').removeClass('uploaded');
    });


    //	Upload multiple images
    var multiPhotoDisplay;

    $('#attach-img').on('change', function (e) {
        return multiPhotoDisplay(this);
    });

    multiPhotoDisplay = function (input) {
        var file, i, len, reader, ref;
        if (input.files && input.files[0]) {
            ref = input.files;
            for (i = 0, len = ref.length; i < len; i++) {
                file = ref[i];
                reader = new FileReader();
                reader.onload = function (e) {
                    var image_html;
                    image_html = "<li><div class=\"img\" style='background-image: url(" + e.target.result + ")'></div><button class=\"delete-img\">X Удалить</button></li>";
                    $('#uploaded-images-list').append(image_html);
                };
                reader.readAsDataURL(file);
            }
        }
    };

    $(document).on("click", "#uploaded-images-list .delete-img", function () {
        $(this).closest("li").remove();
    });

    $(".product-count .btn-minus").addClass("btn-disabled");

        $(".product-count .input-number").on("change", function () {
            if ($(this).val() == 1) {
                $(this).closest(".product-count").find(".btn-minus").addClass("btn-disabled");
            } else {
                $(this).closest(".product-count").find(".btn-minus").removeClass("btn-disabled");
            }
        });



    $('.popup').magnificPopup({
        type: 'inline',
        mainClass: 'mfp-fade'
    });

    $(".tab-menu li a").on("click", function (e) {
        e.preventDefault();
        $(this).closest(".tab-menu").find("li").removeClass("active");
        $(this).closest("li").addClass("active");
        var index = $(this).closest("li").index();
        $(".tab-content-item").removeClass("active");
        $(".tab-content-item").eq(index).addClass("active");
    });

    $(".tab-menu2 li a").on("click", function (e) {
        e.preventDefault();
        $(this).closest(".tab-menu2").find("li").removeClass("active");
        $(this).closest("li").addClass("active");
        var index = $(this).closest("li").index();
        $(".tab-content2 .tab-content-item").removeClass("active");
        $(".tab-content2 .tab-content-item").eq(index).addClass("active");
    });

    $(".phone-number-input").inputmask({
        "mask": "+7 (999)-999-999-9",
    });

    const starEls = document.querySelectorAll('.star.rating');
    starEls.forEach(star => {
        star.addEventListener('click', function (e) {
            let starEl = e.currentTarget;
            console.log(starEl.parentNode.dataset.stars + ", " + starEl.dataset.rating);
            starEl.parentNode.setAttribute('data-stars', starEl.dataset.rating);
        });
    });

    if (!$('.big-slider').hasClass('slick-initialized')) {
        $('.big-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
            nextArrow: $('.big-slider-wrap .arrow-next'),
            prevArrow: $('.big-slider-wrap .arrow-prev'),
            asNavFor: '.small-slider',
        });
    }

    if (!$('.small-slider').hasClass('slick-initialized')) {
        $('.small-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            vertical: true,
            infinite: true,
            focusOnSelect: true,
            asNavFor: '.big-slider',
            nextArrow: $('.small-slider-wrap .arrow-next'),
            prevArrow: $('.small-slider-wrap .arrow-prev'),
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 4,
                        vertical: false,
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 3,
                        vertical: false,
                    }
                },
            ]

        });
    }

    $(".filter-drop-wrap h3").on("click", function () {
        $(this).find("img").toggleClass("rotate");
        $(this).closest(".filter-drop-wrap").find(".hide-wrap").toggleClass("open");
        $(this).closest(".filter-drop-wrap").find(".hide-wrap-2").toggleClass("open");
    })

    var langArray = [];
    $('.vodiapicker option').each(function () {
        var img = $(this).attr("data-thumbnail");
        var text = this.innerText;
        var value = $(this).val();
        var item = '<li><img src="' + img + '" alt="" value="' + value + '"/><span>' + text + '</span></li>';
        langArray.push(item);
    })

    $('#a').html(langArray);

//Set the button value to the first el of the array
    $('.btn-select').html(langArray[0]);
    $('.btn-select').attr('value', 'en');

//change button stuff on click
    $('#a li').click(function () {
        var img = $(this).find('img').attr("src");
        var value = $(this).find('img').attr('value');
        var text = this.innerText;
        var item = '<li><img src="' + img + '" alt="" /><span>' + text + '</span></li>';
        $('.btn-select').html(item);
        $('.btn-select').attr('value', value);
        $(".b").toggle();
        //console.log(value);
    });

    $(".btn-select").click(function () {
        $(".b").toggle();
    });

//check local storage for the lang
    var sessionLang = localStorage.getItem('lang');
    if (sessionLang) {
        //find an item with value of sessionLang
        var langIndex = langArray.indexOf(sessionLang);
        $('.btn-select').html(langArray[langIndex]);
        $('.btn-select').attr('value', sessionLang);
    } else {
        var langIndex = langArray.indexOf('ch');
        console.log(langIndex);
        $('.btn-select').html(langArray[langIndex]);
        //$('.btn-select').attr('value', 'en');
    }
    const lang = document.documentElement.lang;
    $('.favorites__header__right .form-control').change(function () {
        $.ajax({
            type: "GET",
            url: "/customer/favorites",
            data: { 'brand': $('#brand-filter').val(), 'category': $('#category-filter').val()},
            cache: false,
            success: function(data)
            {
                let html = ''
                for (let i = 0; i < data.length; i++) {
                    html += '<div class="sale-item">\n' +
                        '<a href="#" class="img-wrap"><img src="'+ window.location.origin+data[i]['image'][0]['file'] +'" alt=""></a>\n' +
                        '<a class="delete-product" href="'+ window.location.origin+"/customer/del/"+data[i]['id'] +'">&times;</a>\n' +
                        '<p><span class="new">'+ data[i]['price'] +' ₸</span></p>\n' +
                        '<span class="text">'+ data[i]['name_'+lang] +'</span>\n' +
                        '</div>'
                }
                $('#sale-items').html(html)
            }
        })
    })

    $('#addFavorites').click(function () {
        $.ajax({
            type: "GET",
            url: "/favorites/add",
            data: { 'item': $(this).data('item')},
            cache: false,
            success: function(data)
            {
                if (data.status === 1) {
                    $('#addFavorites').addClass('has')
                } else if (data.status === 0) {
                    $('#addFavorites').removeClass('has')
                } else {
                    console.log(data.status)
                }
            }
        })
    })

    $('#reviewSubmit').click(function () {
        let id = $('#reviews-item').data('item')
        let text = $('#reviews-subject').val()
        let rating = $("input[type='radio']:checked").val()

        if (rating === undefined) {
            $('.errors-reviews').addClass('show')
        } else {
            $.ajax({
                type: "GET",
                url: "/customer/review",
                data: { 'id': id, 'text': text, 'rating': rating },
                cache: false,
                success: function(data)
                {
                    if (data.status) {
                        const html = '<div class="order-success">Спасибо за ваш отзыв <br> нам очень приятно</div>'
                        $('.order-reviews__body').html(html)
                    }
                }
            })
        }
    })

    $("input[type='radio']").change(function () {
        if ($("input[type='radio']").val() !== undefined) {
            $('.errors-reviews').removeClass('show')
        }
    })

    $('#orders_status').change(function () {
        let lang = {
            ru: {
                status_1: 'Собирается',
                status_2: 'Отправлен',
                status_3: 'В пути',
                status_4: 'Доставлен'
            },
            kz: {
                status_1: 'Жинақталуда',
                status_2: 'Жіберілді',
                status_3: 'Жолда',
                status_4: 'Жеткізілген'
            },
            tr: {
                status_1: 'Gidiyor',
                status_2: 'Gönderildi',
                status_3: 'Yolumun üzerinde',
                status_4: 'Teslim edilmiş'
            }
        }
        $.ajax({
            type: "GET",
            url: "/customer/orders",
            data: { 'status': $(this).val() },
            cache: false,
            success: function(data)
            {
                let html = ''
                for (let i = 0; i < data.length; i++) {
                    let doneIcon = '';
                    let left = '';

                    if (data[i]['status'] === 4) {
                        doneIcon = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                            '<path d="M6 0C2.68575 0 0 2.68575 0 6C0 9.31425 2.68575 12 6 12C9.31425 12 12 9.31425 12 6C12 2.68575 9.31425 0 6 0ZM9.183 9.183C8.33175 10.0313 7.20225 10.5 6 10.5C4.79775 10.5 3.66825 10.0313 2.817 9.183C1.96875 8.33175 1.5 7.20225 1.5 6C1.5 4.79775 1.96875 3.66825 2.817 2.817C3.66825 1.96875 4.79775 1.5 6 1.5C7.20225 1.5 8.33175 1.96875 9.183 2.817C10.0313 3.66825 10.5 4.79775 10.5 6C10.5 7.20225 10.0313 8.33175 9.183 9.183ZM8.088 3.687L5.262 6.82725L3.9465 5.529L2.892 6.5955L5.325 9L9.20175 4.68975L8.088 3.687Z" fill="#37C155"/>\n' +
                            '</svg>\n'
                    }
                    let statusClass = data[i]['left_status'] === "0" ? 'class="red-text"' : 'class="green-text"'
                    if (data[i]['status'] !== 4) {
                        left = '&nbsp;<span '+ statusClass +'>'+data[i]['left']+'</span>'
                    }

                    html += `<tr ${data[i]['status'] === 4 ? 'class="done"' : ''} >\n` +
                        '<td>\n' +
                        `${data[i]['status'] === 4 ? '<span class="dote"></span>' : ''}\n` +
                        '<span>' + data[i]['id'] + '</span>\n' +
                        '</td>\n' +
                        '<td><span>' + data[i]['delivery_dt_start'] + '</span></td>\n' +
                        '<td>\n' +
                        '<span>' + data[i]['delivery_dt_end'] + ' </span> '+ left +'\n' +
                        '</td>\n' +
                        '<td><span>' + data[i]['price'] + ' ₸</span></td>\n' +
                        '<td><span>-</span></td>\n' +
                        '<td>\n' +
                        `<span <tr ${data[i]['status'] === 4 ? 'class="green-text"' : 'class="red-text"'}>\n` +
                        doneIcon +
                        lang[`${document.documentElement.lang}`][`status_${data[i]['status']}`] +
                        '</span>\n' +
                        '</td>\n' +
                        '<td><span>Отменить</span></td>\n' +
                        '<td>\n' +
                        `<a href="${ window.location.origin+"/customer/order/"+data[i]['id'] }" class="arrow">\n` +
                        'Подробнее\n' +
                        '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<circle opacity="0.7" cx="12.5" cy="12.5" r="12.5" transform="matrix(-1 0 0 1 25 0)" fill="#DBDBDB"/>\n' +
                        '<path opacity="0.7" d="M11.5545 16.8179L15.4043 13.0455L11.5545 9.27333L10.4043 10.4511L13.0521 13.0455L10.4043 15.6401L11.5545 16.8179Z" fill="#2D2929"/>\n' +
                        '</svg>\n' +
                        '</a>\n' +
                        '</td>\n' +
                        '</tr>'
                }
                $('#table-body').html(html)
            }
        })
    })

    jQuery(".calc-delivery").click(function (){
        const column = $(this).closest('tr')
        let id     = column.find('td .order-id').text()
        let weight = column.find('td .logist-table__input').val()
        const btn = $(this)
        btn.html('<i class="fa fa-spinner fa-spin"></i>')

        $.ajax({
            url: "/api/calc-delivery",
            type: "GET",
            dataType: 'json',
            data: {
                id: id,
                weight: weight
            },
            success: function (data) {
                btn.html('<i class="fa-solid fa-rotate-right"></i>')
                column.find('td .logist-table__dp').text(data[0]+' ₸')
                column.find('td .logist-table__tdp').text(data[1]+' ₸')
            }
        });
    })

    $('#select-all__checked').on('click', function () {
        $('.add-to__f103').prop( "checked", true )
        let checkedCount = $('input.add-to__f103:checked').length
        $('.checked-count').text(checkedCount)
        if  (checkedCount > 489) {
            $('#orderExport-send').prop('disabled', true)
            $('.customer-orders__filter h4 small').removeClass('green')
            $('.customer-orders__filter h4 small').addClass('red')
        } else {
            $('#orderExport-send').prop('disabled', false)
            $('.customer-orders__filter h4 small').addClass('green')
            $('.customer-orders__filter h4 small').removeClass('red')
        }
    })

    $('#unselect-all__checked').on('click', function () {
        $('.add-to__f103').prop( "checked", false )
        $('.checked-count').text('0')
        $('#orderExport-send').prop('disabled', false)
        $('.customer-orders__filter h4 small').removeClass('red')
        $('.customer-orders__filter h4 small').removeClass('green')
    })

    $('.add-to__f103').on('click', function () {
        let checkedCount = $('input.add-to__f103:checked').length
        $('.checked-count').text(checkedCount)
        if (checkedCount > 489) {
            $('#orderExport-send').prop('disabled', true)
            $('.customer-orders__filter h4 small').addClass('red')
            $('.customer-orders__filter h4 small').removeClass('green')
        } else if (checkedCount === 0) {
            $('.customer-orders__filter h4 small').removeClass('red')
            $('.customer-orders__filter h4 small').removeClass('green')
            $('#orderExport-send').prop('disabled', true)
        } else {
            $('#orderExport-send').prop('disabled', false)
            $('.customer-orders__filter h4 small').addClass('green')
            $('.customer-orders__filter h4 small').removeClass('red')
        }
    })



    $('#acceptance-checked_all').on('click', function () {
        $('.acceptance-add').prop( "checked", true )
        let checkedCount = $('input.acceptance-add:checked').length
        $('.checked-count').text(checkedCount)
    })

    $('#acceptance-unchecked_all').on('click', function () {
        $('.acceptance-add').prop( "checked", false )
        $('.checked-count').text('0')
    })

    $('.acceptance-add').on('click', function () {
        let checkedCount = $('input.acceptance-add:checked').length
        $('.checked-count').text(checkedCount)
    })

    $("#top").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
});


