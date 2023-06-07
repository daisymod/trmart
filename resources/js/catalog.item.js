jQuery(document).ready(function () {
    jQuery("body").on("click", ".relation-form-open-catalog", function () {
        jQuery(this).relationField({onChange: onChange});
    });

    function onChange(e, config) {
        updateFields();
    }

    if (jQuery(".relation-form-open-catalog").length > 0) {
        updateFields();
    }

    function updateFields() {
        let box = jQuery(".relation-form-open-catalog").parent(".relation-form-box");
        let selectId = [];
        jQuery(".relation-id", box).each(function () {
            selectId[selectId.length] = jQuery(this).data("id");
        });

        jQuery.ajax({
            url: "/field/catalog/fields",
            type: "GET",
            data: {selectId: selectId},
            success: function (data) {
                for (let key in data.all) {
                    let val =  data.all[key];
                    jQuery(".form-field-box-"+val.field).css("display", "none");
                }
                for (let key in data.select) {
                    let val =  data.select[key];
                    jQuery(".form-field-box-"+val.field).css("display", "block");
                }
            }
        });
    }
/*
    $(window).on('beforeunload', function(){
        return "В случае подтверждения закрытия окна браузера, все несохраненные данные будут утеряны.";
    });*/
});
