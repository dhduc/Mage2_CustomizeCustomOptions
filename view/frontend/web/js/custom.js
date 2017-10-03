
require(
    ['jquery', 'ko'],
    function ($) {

        $('select.product-custom-option').on('change', function () {

            var selectid = $(this).attr('id');
            var label = $("label[for='"+selectid+"']");

            $('#colorfor'+selectid).remove();
            label.after('<span id="colorfor'+selectid+'"></span>');
            $(this).find('option:selected').each( function (i, sel) {
                var tit = $(sel).attr("data-src");
                if(tit != '' && typeof tit != 'undefined' && tit !== null) {
                    var datasrc = tit.split("||");
                    $('#colorfor' + selectid).append('<span style="background:' + datasrc[1] + '; width: 70px; height: 70px; display: inline-block"></span>');
                }

            });

        });
    }
);

