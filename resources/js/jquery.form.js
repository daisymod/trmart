jQuery(document).ready(function () {
    //сделаем общий JS для стандартных форм
    jQuery("form", ".form-ajax").submit(function () {
        jQuery(this).formAjax();
        return false;
    });
    jQuery(".field-images-box").imageBox();
    jQuery(".field-images-box").on("click", ".image-box .edit", function () {
        imagesBox = jQuery(this).parents(".field-images-box").eq(0);
        imageWidth = jQuery(imagesBox).attr("data-width");
        imageHeight = jQuery(imagesBox).attr("data-height");
        imageField = jQuery(imagesBox).attr("data-filed");
        imagesBox = jQuery(this).parents(".image-box");
        imageName = imagesBox.attr("data-name");
        jQuery("#modalCropper #image").cropper("destroy");
        jQuery("#modalCropper #image").attr("src", imagesBox.attr("data-file"));
        jQuery("body").addClass("open-dialog");
        jQuery("#image-model-dialog").addClass("open");

        //jQuery("#modalCropper").modal({backdrop: 'static', keyboard: false});
        //jQuery('#modalCropper').on('shown.bs.modal', function (e) {
        jQuery("#modalCropper #image").cropper({
            aspectRatio: imageWidth / imageHeight,
            zoomOnWheel: false,
            dragMode: "none",
        });
        //});
    });

    jQuery(".field-password-box input[type=checkbox]").change(function () {
        if (jQuery(this).prop("checked") == true) {
            jQuery(".field-password-box .form-control").css("display", "block");
        } else {
            jQuery(".field-password-box .form-control").css("display", "none");
        }
    });

    jQuery(".model-dialog .modal-close").click(function () {
        jQuery("body").removeClass("open-dialog");
        jQuery("#image-model-dialog").removeClass("open");
    });

    jQuery(".images-list-box").disableSelection();

    jQuery(".images-list-box").sortable({
        placeholder: "image-box image-box-placeholder",
        start: function (event, ui) {
            jQuery(".image-box-placeholder").css("height", jQuery(ui.item).height());
        },
        update: function (event, ui) {
        }
    });
});

(function (jQuery) {
    jQuery.fn.formAjax = function (options) {
        let config = jQuery.extend({
            url: jQuery(this).attr("action"),
            box: jQuery(this).parents(".form-ajax"),
            onSuccess: onSuccess,
            onError: onError
        }, jQuery(self).data(), options);

        let optionsSubmit = {
            url: config.url,
            type: "POST",
            dataType: "json",
            beforeSubmit: function (data) {
                jQuery(".is-invalid", config.box).removeClass("is-invalid");
                jQuery(".errors-field-text", config.box).text("");
                jQuery(".load-box", config.box).css("display", "block");
            },
            complete: function () {
                jQuery(".load-box", config.box).css("display", "none");
            },
            success: config.onSuccess,
            error: config.onError
        };
        jQuery(this).ajaxSubmit(optionsSubmit);

        function onError(data) {
            for (let key in data.responseJSON) {
                let val = data.responseJSON[key];
                jQuery(".errors-field-text.errors-" + key + "", config.box).text(val);
                jQuery("*[name=" + key + "]", config.box).addClass("is-invalid");
            }
        }

        function onSuccess(data) {
            if (data.text !== null) {
                jQuery(config.box).addClass("content-body").html(data.text);
            }
            if (data.reload == true) {
                location.reload();
            }
            if (data.redirect !== undefined) {
                window.location.href = data.redirect;
            }
            if (data.scroll !== undefined) {
                jQuery('body,html').animate({scrollTop: data.scroll}, 500);
            }
        }

        return self;
    };
})(jQuery);




