jQuery(document).ready(function () {
    jQuery(".select-all").change(function (){
        let box = jQuery(this).parents("table").find("tbody");
        jQuery("input[type=checkbox]").prop("checked", jQuery(this).prop("checked"));
    });
});
