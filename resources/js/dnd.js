jQuery(document).ready(function () {
    let IdOnStartDrag = new Array();
    jQuery(".table-dnd").tableDnD({
        onDragClass: "thisDragRow",
        dragHandle: ".dragRow",
        onDragStart: function (table, row) {
            IdOnStartDrag = new Array();
            jQuery("tbody tr .dragRow", table).each(function (e) {
                IdOnStartDrag[IdOnStartDrag.length] = jQuery(this).attr("data-id");
            });
        },
        onDragStop: function (table, row) {
            let nowId = new Array();
            jQuery("tbody tr .dragRow", table).each(function (e) {
                nowId[nowId.length] = jQuery(this).attr("data-id");
            });
            let shiftElements = new Array();
            for (let i = 0; i < IdOnStartDrag.length; i++) {
                if (IdOnStartDrag[i] !== nowId[i]) {
                    shiftElements[shiftElements.length] = new Array(nowId[i], IdOnStartDrag[i]);
                }
            }
            jQuery.ajax({
                type: "POST",
                data: ({row: shiftElements}),
                success: function (html) {}
            });
        }
    });

});
