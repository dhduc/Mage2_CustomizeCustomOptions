/**
 * Created by kienpham on 5/16/17.
 */


    function uploadOptionImage(input) {
        if ( input.files && input.files[0]) {
            require([
                'jquery'
            ],function ($) {
                var url_upload = window.base_url + "admin/customoption/upload/upload";
                var url_media = "pub/media/CustomOptions";
                var form_data = new FormData();
                var file_data = input.files[0];
                var form_key = $('name[form_key]');

                form_data.append("image", file_data);
                form_data.append("form_key", FORM_KEY);

                var inputHiddenId = $(input).attr('id').replace("upload", "image");

                $.ajax({
                    url: url_upload,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    showLoader: true,
                    success: function (data) {
                      // load value for hidden input image
                        var inputHidden = $("input[id=" + inputHiddenId + "]");
                        inputHidden.val("/" + url_media + data.file).change();

                      // load src for preview image
                        var previewId = $(input).attr('id').replace("upload", "preview");
                        var output = $("img[id=" + previewId + "]");
                        output.attr("src",window.base_url + url_media + data.file);
                    }
                });
            });
        }
    }



