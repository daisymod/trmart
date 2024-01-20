jQuery(document).ready(function () {

    $.ajax({
        url: "/set-lang",
        type: "POST",
        success: function (data) {

        }
    });

    jQuery('.sale-item').init(function() {
        var currentElement = $(this);
        var value = currentElement.attr('data-price');
        var result  = getCountPrice(value ?? currentElement.val() )

        currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));

    });

    if(typeof(localStorage.getItem('country_id')) == "undefined" || localStorage.getItem('country_id') == null) {
        localStorage.setItem('country_id',2);
    }

    if(typeof(localStorage.getItem('city_id')) == "undefined" || localStorage.getItem('city_id') == null) {
        localStorage.setItem('city_id', $("#city_id").text());
    }

    jQuery("#country-list").init(function (){
        $.ajax({
            url: "/api/country/get",
            type: "GET",
            dataType: 'json',

            success: function (data) {
               document.cookie = "currency_id="+localStorage.getItem('country_id');
                var formoption = "";
                $.each(data.items, function(v) {
                    var val = data.items[v]
                    if (localStorage.getItem('lang') == 'ru'){
                        formoption += "<option value='" + val.id + "'>" + val.name_ru + "</option>";
                    }else{
                        formoption += "<option value='" + val.id + "'>" + val.name_en + "</option>";
                    }
                });

                $('#country-list').html(formoption);


                $('#country-list option[value='+localStorage.getItem('country_id') +']').prop('selected', true);
                switch (localStorage.getItem('country_id')){
                    case '1':
                        localStorage.setItem('currency_id','3');
                        localStorage.setItem('symbol','₽');
                        localStorage.setItem('country_id', '1')
                        document.cookie = "currency_id=3";
                        if (document.getElementById('currency_id') != undefined) {
                            document.getElementById('currency_id').value = 3;
                        }
                        break;
                    case '2':
                        if (document.getElementById('currency_id') != undefined) {
                            document.getElementById('currency_id').value = 2;
                        }
                        if (document.getElementById('currency_symbol') != undefined) {
                            document.getElementById('currency_symbol').innerHTML = '₺l';
                        }
                        document.cookie = "currency_id=2";
                        localStorage.setItem('currency_id','2');
                        localStorage.setItem('symbol','₺l');
                        localStorage.setItem('country_id', '2')
                        if (document.getElementById('price-product-tr') != undefined) {
                            document.getElementById('price-product-tr').style.display = 'block';
                            document.getElementById('price-product-kz').style.display = 'none';

                            document.getElementById('total-price-product-tr').style.display = 'block';
                            document.getElementById('total-price-product-kz').style.display = 'none';

                            document.getElementById('total-with-delivery-price-product-tr').style.display = 'block';
                            document.getElementById('total-with-delivery-price-product-kz').style.display = 'none';
                        }
                        break;
                    case '3':
                        if (document.getElementById('currency_id') != undefined) {
                            document.getElementById('currency_id').value = 1;
                        }
                        if (document.getElementById('currency_symbol') != undefined) {
                            document.getElementById('currency_symbol').innerHTML = '₸';
                        }
                        if (document.getElementById('price-product-tr') != undefined) {
                            document.getElementById('price-product-tr').style.display = 'none';
                            document.getElementById('price-product-kz').style.display = 'block';

                            //document.getElementById('total-price-product-tr').style.display = 'none';
                            document.getElementById('total-price-product-kz').style.display = 'block';

                            document.getElementById('total-with-delivery-price-product-tr').style.display = 'none';
                            document.getElementById('total-with-delivery-price-product-kz').style.display = 'block';
                        }
                        document.cookie = "currency_id=1";
                        localStorage.setItem('currency_id','1');
                        localStorage.setItem('symbol','₸');
                        localStorage.setItem('country_id', '3')
                        break;
                }

                $.ajax({
                    url: "/api/currency/calculate",
                    type: "GET",
                    dataType: 'json',
                    data: {
                        currency_id: localStorage.getItem('currency_id') ?? 2
                    },
                    success: function (data) {
                        localStorage.setItem('coefficient',data.data);
                        jQuery('.total').each(function(index , element) {
                            var currentElement = $(this);
                            var value = currentElement.attr('data-price');
                            var result  = getCountPrice(value ?? currentElement.val() )
                            console.log(result)
                            currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
                        });

                        jQuery('.price-product').each(function(index , element) {
                            var currentElement = $(this);
                            var value = currentElement.attr('data-price');
                            var result  = getCountPrice(value ?? currentElement.val() )
                            console.log(result)
                            currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));

                        });

                        jQuery('.old-price').each(function(index , element) {
                            var currentElement = $(this);
                            var value = currentElement.attr('data-old-price');
                            var result  = getCountPrice(value ?? currentElement.val() )
                            currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
                        });
                    }
                });

                $('#symbol-data').html(localStorage.getItem('symbol'));

            }
        });
    })

    function setCurrency(country_id){
        switch (country_id){
            case '1':
                localStorage.setItem('currency_id','3');
                localStorage.setItem('symbol','₽');
                localStorage.setItem('country_id', '1')
                break;
            case '2':
                localStorage.setItem('currency_id','2');
                localStorage.setItem('symbol','₺l');
                localStorage.setItem('country_id', '2')
                break;
            case '3':
                localStorage.setItem('currency_id','1');
                localStorage.setItem('symbol','₸');
                localStorage.setItem('country_id', '3')
                break;
        }
    }


    jQuery('.old-price').each(function() {
        var currentElement = $(this);
        var value = currentElement.text();
        var result  = getCountPrice(value)
        currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
    });


    function getCountPrice(data) {
        var price = data.replace(/ /g, '');
        var result  = parseFloat(price).toFixed(2) * parseFloat(localStorage.getItem('coefficient')).toFixed(2);

        return result.toFixed(2);
    }


    jQuery("#country-delivery-id").init(function (){
        $.ajax({
            url: "/api/country/get",
            type: "GET",
            dataType: 'json',

            success: function (data) {
                var formoption = "";
                $.each(data.items, function(v) {
                    var val = data.items[v]
                    if (localStorage.getItem('lang') == 'ru'){
                        formoption += "<option value='" + val.id + "'>" + val.name_ru + "</option>";
                    }else{
                        formoption += "<option value='" + val.id + "'>" + val.name_en + "</option>";
                    }
                });
                $('#country-delivery-id').html(formoption);
                $('#country-delivery-id option[value='+localStorage.getItem('country_id') +']').prop('selected', true);

            }
        });
    })


    jQuery("#country-delivery-id").change(function (){
        var id = $(this).val()
        $.ajax({
            url: "/api/city/get/" + id,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                var formoption = "";
                $.each(data.items, function (v) {
                    var val = data.items[v]
                    if (localStorage.getItem('lang') == 'ru') {
                        formoption += "<option value='" + val.id + "'>" + val.name_ru + "</option>";
                    } else {
                        formoption += "<option value='" + val.id + "'>" + val.name_en + "</option>";
                    }
                });
                $('#city-delivery-id').html(formoption);
                $('#city-delivery-id option:first').prop('selected', true);
            }
        });
    })

    jQuery("#profile-country_id").change(function (){
        $('#profile-region_id').html('<option selected disabled>Выберите область</option>');
        $('#profile-area_id').html('<option selected disabled>Выберите регион</option>');
        $('#profile-city_id').html('<option selected disabled>Выберите город</option>');
        $('#profile-postcode').html('<option selected disabled>Выберите индекс</option>');
        const id = $(this).val()
        $('#profile-country_title').val($("option:selected", this).text())
        $.ajax({
            url: "/api/region/get/" + id,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                let list = "<option selected disabled>Выберите область</option>";
                $.each(data.items, function (value) {
                    const val = data.items[value]
                    if (localStorage.getItem('lang') == 'ru') {
                        list += "<option value='" + val.id + "'>" + val.name + "</option>";
                    } else {
                        list += "<option value='" + val.id + "'>" + val.name + "</option>";
                    }
                });
                $('#profile-region_id').html(list);
                $('#profile-region_id option:first').attr('selected','selected');
            }
        });
    })

    jQuery("#profile-region_id").change(function (){
        $('#profile-area_id').html('<option selected disabled>Выберите регион</option>');
        $('#profile-city_id').html('<option selected disabled>Выберите город</option>');
        $('#profile-postcode').html('<option selected disabled>Выберите индекс</option>');
        const id = $(this).val()
        $('#profile-region_title').val($("option:selected", this).text())
        $.ajax({
            url: "/api/area/get/" + id,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                let list = "<option selected disabled>Выберите регион</option>";
                $.each(data.items, function (value) {
                    const val = data.items[value]
                    list += "<option value='" + val.id + "'>" + val.name + "</option>";
                });
                $('#profile-area_id').html(list);
                $('#profile-area_id option:first').attr('selected','selected');
            }
        });
    })

    jQuery("#profile-area_id").change(function (){
        $('#profile-postcode').html('<option selected disabled>Выберите индекс</option>');
        const id = $(this).val()
        $('#profile-area_title').val($("option:selected", this).text())
        $.ajax({
            url: "/api/city/get/" + id,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                let list = "<option selected disabled>Выберите город</option>";
                $.each(data.items, function (value) {
                    const val = data.items[value]
                    list += "<option value='" + val.id + "'>" + val.name + "</option>";
                });
                $('#profile-city_id').html(list);
                $('#profile-city_id option:first').attr('selected','selected');

            }
        });
    })

    jQuery("#profile-city_id").change(function (){
        $('#profile-postcode').html('<option selected disabled>Выберите индекс</option>');
        const id = $("option:selected", this).val()
        $('#profile-city_title').val($("option:selected", this).text())
        $.ajax({
            url: "/api/postcode/get/" + id,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                let list = "<option selected disabled>Выберите индекс</option>";
                $.each(data.items, function (value) {
                    const val = data.items[value]
                    list += "<option value='" + val.id + "'>" + val.title + "</option>";
                });
                $('#profile-postcode').html(list);
                $('#profile-postcode option:first').attr('selected','selected');

            }
        });
    })

    jQuery("#profile-postcode").change(function (){
        $('#profile-postcode_id').val($("option:selected", this).text().split(',')[0])
    })

    jQuery("#profile-city_id").on('blur', function (){
        console.log($(this).val())
    })


    $("#city-delivery-id").select2({
        tags: true
    });

    jQuery("#city-delivery-id").init(function (){
        $.ajax({
            url: "/api/city/get/"+localStorage.getItem('country_id'),
            type: "GET",
            dataType: 'json',
            success: function (data) {
                var formoption = "";
                $.each(data.items, function(v) {
                    var val = data.items[v]
                    if (localStorage.getItem('lang') == 'ru'){
                        formoption += "<option value='" + val.id + "'>" + val.name_ru + "</option>";
                    }else{
                        formoption += "<option value='" + val.id + "'>" + val.name_en + "</option>";
                    }
                });
                $('#city-delivery-id').html(formoption);
                $('#city-delivery-id option[value='+localStorage.getItem('city_id') +']').prop('selected', true);
            }

        });
    })

    jQuery("#country-list").change(function (){
        var id = $(this).val()

        document.cookie = "currency_id="+id;
      /*  $.ajax({
            url: "/api/city/get/"+id,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                localStorage.setItem('country_id',id);
                var formoption = "";
               /* $.each(data.items, function(v) {
                    var val = data.items[v]
                    if (localStorage.getItem('lang') == 'ru'){
                        formoption += "<option value='" + val.id + "'>" + val.name_ru + "</option>";
                    }else{
                        formoption += "<option value='" + val.id + "'>" + val.name_en + "</option>";
                    }

                });
                $('#city-list').html(formoption);
            }
        });*/
        switch ($(this).val()){
            case '1':
                document.cookie = "currency_id=3";
                localStorage.setItem('currency_id','3');
                localStorage.setItem('symbol','₽');
                localStorage.setItem('country_id', '1')
                if (document.getElementById('currency_id') != undefined) {
                    document.getElementById('currency_id').value = 3;
                }
                break;
            case '2':
                if (document.getElementById('currency_id') != undefined) {
                    document.getElementById('currency_id').value = 2;
                }
                if (document.getElementById('currency_symbol') != undefined) {
                    document.getElementById('currency_symbol').innerHTML = "₺l";
                }

                if (document.getElementById('price-product-tr') != undefined) {
                    document.getElementById('price-product-tr').style.display = 'block';
                    document.getElementById('price-product-kz').style.display = 'none';

                    document.getElementById('total-price-product-tr').style.display = 'block';
                    document.getElementById('total-price-product-kz').style.display = 'none';

                    document.getElementById('total-with-delivery-price-product-tr').style.display = 'block';
                    document.getElementById('total-with-delivery-price-product-kz').style.display = 'none';
                }
                document.cookie = "currency_id=2";
                localStorage.setItem('currency_id','2');
                localStorage.setItem('symbol','₺l');
                localStorage.setItem('country_id', '2')
                break;
            case '3':
                if (document.getElementById('currency_id') != undefined) {
                    document.getElementById('currency_id').value = 1;
                }
                if (document.getElementById('price-product-tr') != undefined){
                    document.getElementById('price-product-tr').style.display = 'none';
                    document.getElementById('price-product-kz').style.display = 'block';

                    //document.getElementById('total-price-product-tr').style.display = 'none';
                    document.getElementById('total-price-product-kz').style.display = 'block';

                    document.getElementById('total-with-delivery-price-product-tr').style.display = 'none';
                    document.getElementById('total-with-delivery-price-product-kz').style.display = 'block';
                }
                if (document.getElementById('currency_symbol') != undefined) {
                    document.getElementById('currency_symbol').innerHTML = "₸";
                }
                document.cookie = "currency_id=1";
                localStorage.setItem('currency_id','1');
                localStorage.setItem('symbol','₸');
                localStorage.setItem('country_id', '3')
                break;
        }

        $.ajax({
            url: "/api/currency/calculate",
            type: "GET",
            dataType: 'json',
            data: {
                currency_id: localStorage.getItem('currency_id') ?? 1
            },
            success: function (data) {
                localStorage.setItem('coefficient',data.data);

                jQuery('.total').each(function(index , element) {
                    var currentElement = $(this);
                    var value = currentElement.attr('data-price');
                    var result  = getCountPrice(value ?? currentElement.val() )

                    currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
                });

                jQuery('.price-product').each(function(index , element) {
                    var currentElement = $(this);
                    var value = currentElement.attr('data-price');
                    var result  = getCountPrice(value ?? currentElement.val() )

                        currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
                });

                jQuery('.old-price').each(function(index , element) {
                    var currentElement = $(this);
                    var value = currentElement.attr('data-old-price');
                    var result  = getCountPrice(value ?? currentElement.val() )

                    currentElement.html(new Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
                   });

            }
        });

        $('#symbol-data').html(localStorage.getItem('symbol'));
    });


    jQuery("#city-list").init(function (){
        $.ajax({
            url: "/api/city/get/"+localStorage.getItem('country_id'),
            type: "GET",
            dataType: 'json',
            success: function (data) {
                var formoption = "";
                $.each(data.items, function(v) {
                    var val = data.items[v]
                    if (localStorage.getItem('lang') == 'ru'){
                        formoption += "<option value='" + val.id + "'>" + val.name_ru + "</option>";
                    }else{
                        formoption += "<option value='" + val.id + "'>" + val.name_en + "</option>";
                    }
                });

                $('#city-list').html(formoption);
                $('#city-list option[value='+localStorage.getItem('city_id') +']').prop('selected', true);
            }
        });
    })


    jQuery("#city-list").change(function (){
        localStorage.setItem('city_id',$(this).val());
    });


    jQuery(".location-box .country-select").change(function () {
        let box = jQuery(this).parents(".location-box");
        let country_text = jQuery(this).find("option:selected").text();
        jQuery(".country-text", box).text(country_text);
        jQuery(".city-text", box).text("-");
        jQuery("input[name=country_title]", box).val(country_text);
        jQuery("input[name=city_id]", box).val(0);
        jQuery("input[name=city_title]", box).val("");
        jQuery(".city-find", box).val("");
    });

    jQuery(".location-box .city-find").autocomplete({
        minChars: 3,
        serviceUrl: "/api/location/city",
        type: "POST",
        noCache: true,
        onSearchStart: function (params) {
            let box = jQuery(this).parents(".location-box");
            jQuery(".load-progress", box).css("display", "block");
            params.countryId = jQuery("select[name=country_id]", box).val();
        },
        onSearchComplete: function (params) {
            let box = jQuery(this).parents(".location-box");
            jQuery(".load-progress", box).css("display", "none");
        },
        onSelect: function (suggestion) {
            console.log(suggestion)
            let box = jQuery(this).parents(".location-box");
            jQuery(".city-text", box).text(suggestion.value);
            jQuery("input[name=city_id]", box).val(suggestion.id);
            jQuery("input[name=city_title]", box).val(suggestion.value);
        }
    });

    jQuery(".location-box .clear-select").click(function () {
        let box = jQuery(this).parents(".location-box");
        //jQuery(".country-text", box).text("");
        jQuery(".city-text", box).text("-");
        //jQuery("input[name=country_title]", box).val(country_text);
        jQuery("input[name=city_id]", box).val(0);
        jQuery("input[name=city_title]", box).val("");
        jQuery(".city-find", box).val("");
    });

    jQuery(".user-find-form .city-find").on("keyup change", function(){
        if (jQuery(this).val() == "") {
            let box = jQuery(this).parents(".location-box");
            jQuery("input[name=city_id]", box).val(0);
            jQuery("input[name=city_title]", box).val("");
        }
    });

    $('#merchant-city_id').on('change', function () {
        $('#merchant-city_title').val($(this).find(":selected").text())
    })

});
