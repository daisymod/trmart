let openModal = 0;

window.createModal = function(title) {
    let modal = jQuery(
"<div class='modal-background'>\n" +
        "   <div class='modal-box'>\n" +
        "       <div class='modal-container'>\n" +
        "           <div class='modal-title'>\n" +
        "               <div class='modal-title-text'>" + title + "</div>\n" +
        "               <div class='modal-close'><i class='fas fa-window-close'></i></div>\n" +
        "           </div>\n" +
        "       <div class='modal-body'></div>\n" +
        "    </div>\n" +
        "   </div>\n" +
        "</div>");
    jQuery(modal).on("click.relationField", ".modal-title .modal-close", function () {
        closeModal(modal);
    });
    jQuery("body").append(modal);
    jQuery("body").addClass("modal-open");
    openModal = openModal + 1;
    return modal;
}

window.closeModal = function(modal) {
    jQuery(modal).remove();
    openModal = openModal - 1;
    if (openModal == 0) {
        jQuery("body").removeClass("modal-open");
    }
}
