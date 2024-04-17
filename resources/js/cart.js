jQuery(document).ready(function () {
/*    jQuery("body").on("click", ".add-to-cart-one", function () {
        jQuery.ajax({
            url: "/cart/add",
            type: "POST",
            data: {
                id: jQuery(this).attr("data-id"),
                size: jQuery(this).attr("data-size"),
                count: 1,
            },
            success: function (data) {
                setCountPrice(data);
            }
        });
    });*/



    jQuery(".add-to-cart-many").click(function () {
        let self = this;

        let color = document.getElementsByClassName('color-data-active slick-active');

        jQuery.ajax({
            url: "/cart/add",
            type: "POST",
            data: {
                id: jQuery(this).attr("data-id"),
                count: parseInt(jQuery(this).parent().find("input").val()),
                size: jQuery(this).parents(".right").find(".variants-radios input:checked").val(),
                color: color[0].dataset.colorid,
                
            },
            success: function (data) {
                setCountPrice(data);
                if (jQuery(self).hasClass("go-now")) {
                    window.location.href = "/cart";
                }
            },
        });
    });

    jQuery(".cart-plus").click(function () {
        var count = parseInt(jQuery(this).parent().find("input").val());
        count = count + 1;
        jQuery(this).parent().find("input").val(count);
        let box = jQuery(this).parents(".line").eq(0);
        updateCountPrice(box,'plus');
        $('.payment-method__block').hide()
        $('#get-del-price').show()
    });

    jQuery(".cart-minus").click(function () {
        var count = parseInt(jQuery(this).parent().find("input").val());
        count = count - 1;
        if (count < 1) {
            count = 1;
        }
        jQuery(this).parent().find("input").val(count);
        let box = jQuery(this).parents(".line").eq(0);
        updateCountPrice(box);
        $('.payment-method__block').hide()
        $('#get-del-price').show()
    });

    jQuery(".line .input-number").keyup(function () {
        let box = jQuery(this).parents(".line").eq(0);
        updateCountPrice(box);
    });

    function updateCountPrice(box,type) {
        let key = jQuery(box).data("key");
        jQuery.ajax({
            url: "/cart/set",
            type: "POST",
            data: {
                key: key,
                count: parseFloat(jQuery(".input-number", box).val())
            },
            success: function (data) {

                var price = data.price.replace(/ /g, '');
                    $('#cart-price').html(Math.ceil(result));
                    var result  = parseFloat(price).toFixed(2) * parseFloat(localStorage.getItem('coefficient')).toFixed(2);

                var delivery =  $('#card-delivery-price').text();
                var deliveryPrice = delivery.replace(/ /g, '');

                var priceWithDelivery = result + parseFloat(deliveryPrice);

                    //$('input[name="price"]').val(Math.ceil(result));
                    jQuery("#cart-price").text(Intl.NumberFormat('ru-RU').format(Math.ceil(result)));
                    jQuery("#total-price-order").text(Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));



                    jQuery("#total-order-price-data").text(Intl.NumberFormat('ru-RU').format(Math.ceil(priceWithDelivery)) + ' ' + localStorage.getItem('symbol'));

                    var totalData = document.getElementsByClassName("total");
                    var InputNumber = document.getElementsByClassName("input-number");

                    for (var i = 0; i < data.items.length; i++) {
                        var calculate = data.items[i].price * InputNumber[i].value * parseFloat(localStorage.getItem('coefficient')).toFixed(2);
                        totalData[i].innerHTML = Math.ceil(calculate) + ' ' + localStorage.getItem('symbol');
                    }



            },
            error: function (error) {

                console.log(11111)
                jQuery.ajax({
                    url: "/cart/set",
                    type: "POST",
                    data: {
                        key: key,
                        count: 0
                    },
                    success: function (data) {
                        jQuery("#count-items-cart").text(data.items.length);
                        setCountPrice(data);
                        jQuery(box).remove()
                        $('.payment-method__block').hide()
                        $('#get-del-price').show()
                    }
                });
            }
        });
    }
    jQuery("#total-order-price-data").change(function () {
        jQuery("#price-order").value($(this).val());
    });

    jQuery(".clear-cart").click(function () {
        jQuery.ajax({
            url: "/cart/del",
            type: "POST",
            success: function (data) {
                jQuery("#product-table-cart").html("");
                jQuery("#count-items-cart").text(0);
                setCountPrice(data);
                location.reload()
            }
        });
    });


    jQuery(".line .cart-del").click(function () {
        let box = jQuery(this).parents(".line");
        let key = jQuery(box).attr("data-key");
        box.remove();
        jQuery.ajax({
            url: "/cart/set",
            type: "POST",
            data: {
                key: key,
                count: 0
            },
            success: function (data) {
                jQuery("#count-items-cart").text(data.items.length);
                setCountPrice(data);
                $('.payment-method__block').hide()
                $('#get-del-price').show()
            }
        });
    });


    jQuery("#get-del-price").click(function () {
        jQuery.ajax({
            url: "/cart/calculate",
            type: "GET",
            success: function () {
                location.reload()
            }
        });
    });


    function setCountPrice(data) {
        jQuery(".cart-count").text(data.count);

        if (data.count > 0) {
            jQuery(".cart-find .cart").addClass("active");
        } else {
            jQuery(".cart-find .cart").removeClass("active");

        }




        var price = data.price.replace(/ /g, '');


        var result = parseFloat(price).toFixed(2) * parseFloat(localStorage.getItem('coefficient')).toFixed(2);


        var delivery =  $('#card-delivery-price').text();

        var deliveryPrice = delivery.replace(/ /g, '');

        var priceWithDelivery = result + parseFloat(deliveryPrice);


        $('#total-order-price-data').text(Intl.NumberFormat('ru-RU').format(Math.ceil(priceWithDelivery))+ ' ' + localStorage.getItem('symbol'));

        jQuery("#total-price-order").text(Intl.NumberFormat('ru-RU').format(Math.ceil(result)) + ' ' + localStorage.getItem('symbol'));
        //jQuery("#total-order-price-data").text(Intl.NumberFormat('ru-RU').format(Math.ceil(resultWithDelivery)) + ' ' + localStorage.getItem('symbol'));
        $('#cart-price').html(Intl.NumberFormat('ru-RU').format(Math.ceil(result)));
    }
});
