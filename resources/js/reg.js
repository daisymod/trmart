jQuery(document).ready(function () {


    jQuery('#UserLogin').click(function (){
        jQuery(".entrance-popup-wrap").show();
        jQuery(".enter-code-wrap-user").hide();
        jQuery(".enter-code-wrap-merchant").hide();
        jQuery(".enter-code-wrap-user-password").hide();
    });

    jQuery('#UserRegister').click(function (){
        jQuery(".entrance-popup-wrap").show();
        jQuery(".enter-code-wrap-user").hide();
        jQuery(".enter-code-wrap-merchant").hide();
        jQuery(".enter-code-wrap-user-password").hide();
    });

    jQuery("form", ".form-ajax-reset-password").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reset-password"),
            onSuccess: function (data) {
                jQuery(".entrance-popup-wrap").hide();
                jQuery(".enter-code-wrap-user .top span").text(data.phone);
                jQuery(".enter-code-wrap-user").show();
            }
        });
        return false;
    });



    jQuery("form", ".form-ajax-reg-user").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reg-user"),
            onSuccess: function (data) {
                jQuery(".entrance-popup-wrap").hide();
                jQuery(".enter-code-wrap-user .top span").text(data.phone);
                jQuery(".enter-code-wrap-user").show();
            }
        });
        return false;
    });

    jQuery("form", ".form-ajax-reg-merchant").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reg-merchant"),
            onSuccess: function (data) {
                jQuery(".registration-popup-wrap").hide();

                document.getElementById('first_name_merchant').value = data.first_name;
                document.getElementById('last_name_merchant').value = data.last_name;
                document.getElementById('patronymic_merchant').value = data.patronymic;
                document.getElementById('email_merchant').value = data.email;
                document.getElementById('company_name_merchant').value = data.company_name;
                document.getElementById('phone_merchant').value = data.phone;
                document.getElementById('password_merchant').value = data.password;

                jQuery(".enter-code-wrap-merchant .top span").text(data.phone);
                jQuery(".enter-code-wrap-merchant").show();
                //window.location.href = "/";
            }
        });
        return false;
    });

    jQuery("form", ".form-ajax-reset-password").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reset-password"),
            onSuccess: function (data) {
                jQuery(".entrance-popup-wrap ").hide();
                jQuery(".enter-code-wrap-user .top span").text(data.phone);
                jQuery(".enter-code-wrap-user").show();
            }
        });
        return false;
    });


    jQuery("form", ".form-ajax-reset-password").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reset-password"),
            onSuccess: function (data) {
                jQuery(".entrance-popup-wrap").hide();
                jQuery(".enter-code-wrap-user .top span").text(data.phone);
                jQuery(".enter-code-wrap-user").show();
            }
        });
        return false;
    });


    jQuery("form", ".form-ajax-sms-user").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reset-password"),
            onSuccess: function (data) {
                jQuery(".enter-code-wrap-user").hide();
                document.getElementById('phone-data-for-reset').value = data.phone;
                jQuery(".enter-code-wrap-user-password").show();
            }
        });
        return false;
    });


    jQuery("form", ".form-ajax-sms-user-reset").submit(function () {
        jQuery(this).formAjax({
            box: jQuery(this).parents(".form-ajax-reset-password"),
            onSuccess: function (data) {
                window.location.href = "/";
            }
        });
        return false;
    });


});
