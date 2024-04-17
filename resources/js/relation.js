jQuery(document).ready(function () {
    jQuery("body").on("click", ".relation-form-open", function () {
        jQuery(this).relationField();
    });
    jQuery("body").on("click", ".relation-form-box .relation-ids .relation-id .close", function () {
        jQuery(this).parent(".relation-id").remove();
    });
});

(function (jQuery) {
    jQuery.fn.relationField = function (options) {
        let self = jQuery(this).parent(".relation-form-box");
        let config = jQuery.extend({
            url: "",
            selectId: {},
            parentId: 0,
            ignoreId: {},
            level1Only: false,
            selectParent: true,
            onChange: onChange
        }, jQuery(self).data(), options);
        actionInit();

        let modalTable;
        let page = 1;
        let modalTableQuestion;
        function actionInit() {
            console.log('test');
            config.selectId = {};
            jQuery(".relation-id", self).each(function () {
                config.selectId[jQuery(this).data("id")] = jQuery(this).data("name");
            });

            modalTable = createModal("");
            addFindAndTable(modalTable);
            updateTable();
            for (let id in config.selectId) {
                let name = config.selectId[id];
                jQuery(".relation-ids", modalTable).append(
                    getRelationHtml(id, name)
                );
            }
            jQuery(modalTable).on("click.relationField", ".relation-set-page", function () {
                page = jQuery(this).data("page");
                updateTable();
            });
            jQuery(modalTable).on("click.relationField", ".relation-find", function () {
                page = 1;
                updateTable();
            });
            jQuery(modalTable).on("change.relationField", ".relation-checkbox", function () {
                checkboxChange(this);
            });
            jQuery(modalTable).on("click", ".relation-ids .relation-id .close", function () {
                deleteRelationClick(this);
            });

            jQuery(modalTable).on("click", ".relation-save", function () {


                    saveRelation();
                    config.onChange(self, config);
                    closeModal(modalTable);
            });




            jQuery(modalTable).on("click", ".relation-set-config", function () {
                let data = jQuery(this).data();
                config = jQuery.extend(config, data);
                page = 1;
                updateTable();
            });

        }
        function addQuestion(modal) {
            let find = jQuery("<div class='relation-find-box'>" +
                "<div class='qustion-reset'>" +
                "<p>Replacing the category will clear the characteristics. Continue?</p>" +
                "" +
                "<div class='buttons-data'>" +
                "<button class='btn-accept btn green-btn'>Yes</button>" +
                "<button class='btn-reject btn red-btn'>No</button>" +
                "</div>" +
                "" +
                "" +
                "" +
                "</div>" +
                "" +
                "" +
                "</div>");
            jQuery(".modal-body", modal).append(find);


            return modal;
        }

        function addFindAndTable(modal) {
            let find = jQuery("<div class='relation-find-box'></div>");
            jQuery(".modal-body", modal).append(find);
            jQuery(".modal-body", modal).append("<div class='relation-ids'></div><div class='relation-table-box'></div>");
            jQuery.ajax({
                url: config.url + "/find",
                type: "GET",
                data: {
                    //page: config.page,
                    //ignoreId: config.ignoreId,
                    //find_data: jQuery("form", modalTable).serialize()
                },
                success: function (data) {
                    jQuery(find).html(data);
                }
            });
            return modal;
        }

        function updateTable() {
            jQuery.ajax({
                url: config.url + "/list",
                type: "GET",
                data: {
                    page: page,
                    config: {
                        selectId: config.selectId,
                        parentId: config.parentId,
                        ignoreId: config.ignoreId,
                        level1Only: config.level1Only,
                        selectParent: config.selectParent
                    },
                    find_data: jQuery("form", modalTable).serialize(),
                },
                success: function (data) {
                    jQuery(".relation-table-box", modalTable).html(data);
                }
            });
        }

        function checkboxChange(checkbox) {
            let id = jQuery(checkbox).data("id");
            let name = jQuery(checkbox).data("name");
            if (jQuery(checkbox).prop("checked")) {
                if (config.multiple != 1) {
                    jQuery(modalTable).find(".relation-ids").html("");
                    for (let id in config.selectId) {
                        jQuery(modalTable).find(".relation-checkbox[data-id=" + id + "]").prop("checked", false);
                    }
                    config.selectId = {};
                }
                jQuery(modalTable).find(".relation-ids").append(getRelationHtml(id, name));
                config.selectId[id] = name;
            } else {
                jQuery(modalTable).find(".relation-id[data-id=" + id + "]").remove();
                delete config.selectId[id];
            }
        }

        function deleteRelationClick(btn) {
            let box = jQuery(btn).parent(".relation-id");
            let id = jQuery(box).data("id");
            jQuery(box).remove();
            jQuery(".relation-table-box", modalTable).find("input[data-id=" + id + "]").prop('checked', false);
            delete config.selectId[id];
        }

        function saveRelation() {
            jQuery(".relation-id", self).remove();
            for (let id in config.selectId) {
                let name = config.selectId[id];
                jQuery(".relation-ids", self).append(
                    getRelationHtml(id, name)
                );
                jQuery("#category_id_form").val(id);
            }



            closeModal(modalTable);
        }

        function getRelationHtml(id, name) {
            let field = jQuery(self).data("field");
            return '<div class="relation-id" data-id="' + id + '" data-name="' + name + '">\n' +
                '<input type="hidden" name="' + field + '[]" value="' + id + '">' +
                '' + name + '\n' +
                '<div class="close">\n' +
                '<i class="fas fa-times-circle"></i>\n' +
                '</div>\n' +
                '</div>';
        }

        function onChange(e, config) {

        }

        return self;
    };
})(jQuery);
