jQuery(document).ready(function () {
    jQuery(".form-label-lang .lang").click(function () {
        let box = jQuery(this).parents(".form-group");
        jQuery(".form-label-lang .lang", box).removeClass("active");
        jQuery(this).addClass("active");
        let lang = jQuery(this).data("lang");
        jQuery(".form-lang-box", box).removeClass("active");
        jQuery(".form-lang-box-" + lang, box).addClass("active");
    });

    jQuery(".form-lang-top-box .lang").click(function () {
        let box = jQuery(this).parents(".form-main-box");
        jQuery(".form-lang-top-box .lang", box).removeClass("active");
        jQuery(this).addClass("active");
        let lang = jQuery(this).data("lang");
        jQuery(".form-label-lang .lang", box).removeClass("active");
        jQuery(".form-label-lang .lang-" + lang, box).addClass("active");
        jQuery(".form-lang-box", box).removeClass("active");
        jQuery(".form-lang-box-" + lang, box).addClass("active");
    });

});
