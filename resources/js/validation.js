$(document).ready(function () {

    var open = window.XMLHttpRequest.prototype.open,
        send = window.XMLHttpRequest.prototype.send;


    addEventListener('error', (event) => {

        console.log('error')

    })

    function openReplacement(method, url, async, user, password) {

        this._url = url;
        return open.apply(this, arguments);
    }
    function sendReplacement(data) {

        if(this.onreadystatechange) {
            this._onreadystatechange = this.onreadystatechange;
        }
        /**
         * PLACE HERE YOUR CODE WHEN REQUEST IS SENT
         */
        this.onreadystatechange = onReadyStateChangeReplacement;
        return send.apply(this, arguments);
    }
    function onReadyStateChangeReplacement() {

        /**
         * PLACE HERE YOUR CODE FOR READYSTATECHANGE
         */
        if(this._onreadystatechange) {
            return this._onreadystatechange.apply(this, arguments);
        }
        if (this.status == 422){

                if (this.response != ''){
                    if ($(".alert")[0]){

                    } else {

                        var obj = JSON.parse(this.response);
                        var stringError = '';


                        Object.keys(obj).forEach(function(key) {
                            stringError = '  <h3>' +obj[key]+'</h3>\n'
                            //'  <h3>' +obj[key]+'</h3>\n'
                        })



                        $('body').append('<div class="alert danger-alert">\n' +
                                stringError
                            +
                            '  <a class="close-error">&times;</a>\n' +
                            '</div>');




                        setTimeout(function() {
                            $('.danger-alert').delay(5000).fadeOut('slow');
                            $('.danger-alert').remove();
                        }, 5000);

                    }
                }
        }

    }


    function encode_utf8( s ){
        return unescape( encodeURIComponent( s ) );
    }( '\u4e0a\u6d77' )


    window.XMLHttpRequest.prototype.open = openReplacement;
    window.XMLHttpRequest.prototype.send = sendReplacement;




    $(document).on ("click", ".close-error", function () {
        $(this)
            .parent(".alert")
            .fadeOut();

        $(this).parent().remove();
    });


});
