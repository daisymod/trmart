jQuery(document).ready(function () {
    jQuery("body").on("click", "a, button", function () {
        let text = jQuery(this).attr("data-question");
        if (text == undefined || confirm(text)) {
            return true;
        } else {
            return false;
        }
    });
});
