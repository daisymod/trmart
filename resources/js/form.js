jQuery(document).ready(function () {
    var j = 0;

    var dataId = 0


    jQuery("#add-btn-product").click(function (){
        var table = document.getElementById('productTable');
        var totalRowCount = table.rows.length;

        j = totalRowCount

        var color = document.getElementById('color-data');
        var size = document.getElementById('size-data');


        var colorText  = color.innerHTML.replace('addmore[0][color]', 'addmore['+ j +'][color]')
        var sizeText  = size.innerHTML.replace('addmore[0][size]', 'addmore['+ j +'][size]')

        //'                <td><input type="text" name="addmore['+j+'][price]"  class="form-control" /></td>\n' +
        $('#productTable tr:last').after('<tr><td>\n' +
            '                    ' + sizeText + ' \n' +
            '                </td>\n' +
            '                <td>\n' +
            '                    ' + colorText + ' \n' +
            '                </td>\n' +

            '                <td><input type="text" name="addmore['+j+'][count]" class="form-control" /></td>\n' +
            //'                <td><input type="text" name="addmore['+j+'][sale]"  class="form-control" /></td>' +
            '<td><button type="button" onclick="$(this).parents(\'tr\').remove()" class="btn red-btn remove-tr">-</button></td></tr>');
    });




    jQuery("#add-btn-characteristic").click(function () {

        let k = document.getElementById("TableCharacteristic").rows.length - 1;

        var characteristic = document.getElementById('characteristic-data');
        var characteristicText  = characteristic.innerHTML.replace('characteristic[0]', 'characteristic['+ k +']')


        var values = '<td>\n' +
            '                                                            <div class=" active  form-lang-box form-lang-box-ru">\n' +
            '                                    <input type="text" name="value[ru]['+ k +']" class="form-control">\n' +
            '                                    <datalist id="characteristic">\n' +
            '                                                                            </datalist>\n' +
            '                                </div>\n' +
            '                                                            <div class=" form-lang-box form-lang-box-tr">\n' +
            '                                    <input type="text" name="value[tr]['+ k +']" class="form-control">\n' +
            '                                    <datalist id="characteristic">\n' +
            '                                                                            </datalist>\n' +
            '                                </div>\n' +
            '                                                            <div class=" form-lang-box form-lang-box-kz">\n' +
            '                                    <input type="text" name="value[kz]['+ k +']" class="form-control">\n' +
            '                                    <datalist id="characteristic">\n' +
            '                                                                            </datalist>\n' +
            '                                </div>\n' +
            '                                                    </td>'



        $('#TableCharacteristic tr:last').after(
            '<tr><td>\n' +
            '                    ' + characteristicText + ' \n' +
            '                </td>\n' +
            '                '+ values +'\n' +
            '<td><button type="button" onclick="$(this).parents(\'tr\').remove()" class="btn red-btn remove-tr">-</button></td></tr>'
        );

    });

    jQuery(".remove-tr").click(function (){
        console.log($(this));
        $(this).parents('tr').remove();
    });

    function removeTr() {
        $(this).parents('tr').remove();
    }

    var i = 0;

    jQuery("#add-btn").click(function(){
        var table = document.getElementById('dynamicAddRemove');
        var totalRowCount = table.rows.length;

        var form = table.parentElement.parentElement;

        let active = 'RUS';
        for (let index = 0; index < form.children[0].children[1].children.length; index++){
            var elements = Array.from(form.children[0].children[1].children[index].classList)
            if (elements.includes('active')){
                active = form.children[0].children[1].children[index].innerHTML;
            }
        }
        i = totalRowCount - 1;


        var compound  = '<td>\n' +
            '                       <div class="form-lang-box form-lang-box-ru '+ setActive('RUS',active) +'">\n' +
            '                            <input type="text" name="compound[ru]['+ i+']" class="form-control">\n' +
            '                            <datalist id="compound">\n' +
            '                                                            </datalist>\n' +
            '                        </div>\n' +
            '                                            <div class="form-lang-box form-lang-box-tr '+ setActive('TUR',active) +'">\n' +
            '                            <input type="text" name="compound[tr]['+ i+']" class="form-control">\n' +
            '                            <datalist id="compound">\n' +
            '                                                            </datalist>\n' +
            '                        </div>\n' +
            '                                            <div class="form-lang-box form-lang-box-kz '+ setActive('KAZ',active) +'">\n' +
            '                            <input type="text" name="compound[kz]['+ i+']" class="form-control">\n' +
            '                            <datalist id="compound">\n' +
            '                                                            </datalist>\n' +
            '                        </div>\n' +
            '                                    </td>'


        $('#dynamicAddRemove tr:last').after('<tr>'+ compound +'<td><input type="number" min="0" max="100" name="percent['+i+']" class="form-control" /></td><td><button type="button" onclick="$(this).parents(\'tr\').remove()" class="btn red-btn remove-tr">-</button></td></tr>');
    });


    function setActive(active,lang){
        if (active === lang){
            return 'active';
        }else{
            return null;
        }
    }


    jQuery("#upload").click(function(){
        var file_data = $('#fileUpload').prop('files')[0];
        var form_data = new FormData();
        let modal = jQuery(
            "<div class='modal-background'>\n" +
            "   <div class='modal-box'>\n" +
            "       <div class='modal-container'>\n" +
            "           <div class='modal-title'>\n" +
            "               <div class='modal-title-text'> Import catalog items </div>\n" +
            "               <div class='modal-close'><i class='fas fa-window-close'></i></div>\n" +
            "           </div>\n" +
            "       <div class='modal-body'></div>\n" +


            "<div class='loader-box'>\n" +
                "<span class=\"loader\"></span>\n" +
            "</div>\n" +


            "    </div>\n" +
            "   </div>\n" +
            "</div>");


        form_data.append('file', file_data);
        if (file_data != undefined){
            jQuery("body").append(modal);
            jQuery("body").addClass("modal-open");
            jQuery(modal).on("click.relationField", ".modal-title .modal-close", function () {
                closeModal(modal);
            });
            $.ajax({
                url: '/catalog_item/load',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(php_script_response){
                    jQuery(".loader-box").remove();

                    var response = JSON.parse(php_script_response);
                    var html = response.html;
                    jQuery(".modal-body").append(html);

                },


            });
        }

    });


    window.closeModal = function(modal) {
        jQuery(modal).remove();
        jQuery("body").removeClass("modal-open");
    }



});
