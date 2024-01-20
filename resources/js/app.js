window.$ = window.jQuery = require( "jquery" );

jQuery.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
require("devbridge-autocomplete/dist/jquery.autocomplete.min");
require("jquery-ui/ui/core");
require("jquery-ui/ui/widgets/sortable");
require("jquery-ui/ui/disable-selection");
require("jquery-cropper/dist/jquery-cropper");
require("inputmask/dist/jquery.inputmask");
require("jquery-form/dist/jquery.form.min");
require("tablednd");
require("magnific-popup");

require("./script");
require("./select2.min");
require("./slick.min");
require("./images-box");
require("./location");
require("./jquery.validate");
require("./jquery.form");
require("./jquery.inputmask");
require("./modal");
require("./relation");
require("./question");
require("./catalog.item");
require("./dnd");
require("./language");
require("./reg");
require("./select-all");
require("./cart");
require("./docs");
require("./search");
require("./currency");
require("./validation");
require("./auth");
require("./form");
require("./socket");
require("./dynamicFormParser");
import intlTelInput from 'intl-tel-input';
