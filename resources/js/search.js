jQuery(document).ready(function () {

    $('.filter-select').select2({width: "100%",
    });


    jQuery("#search-item").keyup(function () {
        $.ajax({
            url: "/api/shop/find",
            type: "GET",
            dataType: 'json',
            data: {
                lang : localStorage.getItem('lang'),
                find: $(this).val(),
            },
            success: function (data) {
                if (data.items.length > 0){
                    var formoption = "";
                    $.each(data.items, function(v) {
                        var val = data.items[v]
                        var find = val["name_"+localStorage.getItem('lang')];
                        formoption += "<a href=\"/shop/find?find="+find+" \">"+find+"</a>";
                    });
                    $('#search-result').html(formoption);
                    $("#search-result").css("display", "block");
                }else{
                    $("#search-result").css("display", "none");
                }
            }
        });
    });

    jQuery("#search-fields").keyup(function () {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });



});
