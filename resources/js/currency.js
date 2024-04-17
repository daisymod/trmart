jQuery(document).ready(function () {
    if(typeof(localStorage.getItem('currency_id')) == "undefined" || localStorage.getItem('currency_id') == null) {
        localStorage.setItem('currency_id','2');
    }

    if(typeof(localStorage.getItem('coefficient')) == "undefined" || localStorage.getItem('coefficient') == null) {
        localStorage.setItem('coefficient','1');
    }

    if(typeof(localStorage.getItem('coefficient_tenge')) == "undefined" || localStorage.getItem('coefficient') == null) {
        localStorage.setItem('coefficient_tenge','1');
    }


    if(typeof(localStorage.getItem('symbol')) == "undefined" || localStorage.getItem('symbol') == null) {
        localStorage.setItem('symbol', '₸');
    }

    jQuery("#cart-price").init(function () {
        $.ajax({
            url: "/api/currency/calculate",
            type: "GET",
            dataType: 'json',
            data: {
                currency_id: 1
            },
            success: function (data) {
                localStorage.setItem('coefficient_tenge',data.data);
            }
        });
    });


    jQuery("#cart-price").init(function () {
            $.ajax({
                url: "/api/currency/calculate",
                type: "GET",
                dataType: 'json',
                data: {
                    currency_id: localStorage.getItem('currency_id') ?? 1
                },
                success: function (data) {
                    var input = $( "#card_price_turkey" ).text();
                    var processed = input.replace(/ /g, '');
                    localStorage.setItem('coefficient',data.data);
                    var price = processed * data.data;

                    console.log(Math.ceil(price.toFixed(0)))
                    console.log(price)
                    console.log(resultPrice)

                    var priceProduct = $( "#new-price" ).text();

                    var processedProduct = priceProduct.replace(/ /g, '');

                    var resultPrice = parseInt(processedProduct) * data.data;



                    $('#cart-price').html(Math.ceil(price));
                    //$('input[name="price"]').val(Math.ceil(price.toFixed(0)));


                    $('#price-by-currency').text(Intl.NumberFormat('ru-RU').format(Math.ceil(resultPrice))+ ' ' + data.symbol);

                    $('#total-price-order').text(Intl.NumberFormat('ru-RU').format(Math.ceil(price))+ ' ' + data.symbol);

                    var delivery =  $('#card-delivery-price').text();
                    var deliveryPrice = delivery.replace(/ /g, '');

                    var priceWithDelivery = price + parseFloat(deliveryPrice);


                    if (localStorage.getItem('currency_id') == 2){
                        let totalPrice = (price * localStorage.getItem('coefficient_tenge')) + parseFloat(deliveryPrice);
                        $('#total-order-price-data').text(Intl.NumberFormat('ru-RU').format(Math.ceil(totalPrice))+ ' ' + '₸');
                    }else {

                        $('#total-order-price-data').text(Intl.NumberFormat('ru-RU').format(Math.ceil(priceWithDelivery))+ ' ' + '₸');
                    }



                   // localStorage.setItem('symbol',data.symbol)
                    $('#symbol-cart').html(data.symbol);

                }
            });
    });


    jQuery("#country-list").change(function () {
        $.ajax({
            url: "/api/currency/calculate",
            type: "GET",
            dataType: 'json',
            data: {
                currency_id: localStorage.getItem('currency_id') ?? 1
            },
            success: function (data) {
                var input = $( "#card_price_turkey" ).text();
                var processed = input.replace(/ /g, '');
                var price = processed * data.data;

                var priceProduct = $( "#new-price" ).text();
                var processedProduct = priceProduct.replace(/ /g, '');
                var resultPrice = parseInt(processedProduct) * data.data;

                $('#symbol-cart').html(data.symbol);

                $('#price-by-currency').text(resultPrice.toFixed(0)+ ' ' + data.symbol);

                $('#total-price-order').text(price.toFixed(0)+ ' ' + data.symbol);

                var delivery =  $('#card-delivery-price').text();
                var deliveryPrice = delivery.replace(/ /g, '');

                var priceWithDelivery = price + parseFloat(deliveryPrice);


                console.log(localStorage.getItem('currency_id'));
                if (localStorage.getItem('currency_id') == 2){
                    let totalPrice = (price * localStorage.getItem('coefficient_tenge')) + parseFloat(deliveryPrice);
                    $('#total-order-price-data').text(Intl.NumberFormat('ru-RU').format(Math.ceil(totalPrice))+ ' ' + '₸');
                }else {

                    $('#total-order-price-data').text(Intl.NumberFormat('ru-RU').format(Math.ceil(priceWithDelivery))+ ' ' + '₸');
                }



                //$('#total-order-price-data').text(price.toFixed(0)+ ' ' + data.symbol);
                $('#cart-price').html(price.toFixed(0));
                //$('input[name="price"]').val(price.toFixed(0));


                //localStorage.setItem('symbol',data.symbol)
            }
        });
    });

});
