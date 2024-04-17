jQuery(document).ready(function () {
    jQuery(".select-all").change(function (){
        let box = jQuery(this).parents("table").find("tbody");
        document.getElementById('button-action-catalog_item').disabled = false;
        jQuery("input[type=checkbox]").prop("checked", jQuery(this).prop("checked"));
    });
});
