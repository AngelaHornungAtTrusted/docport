(function($) {
    let $documentForm, $fileSelect;
    let fileArray = [];
    let formData = new FormData();
    const pageInit = function() {
        $fileSelect = $('#documentUpload');

        $fileSelect.on('click', openMediaUploader);
    }

    const openMediaUploader = function() {
        'use strict';

        let multipleSelection = true;
        var uploader, imgData, json;

        if ( undefined !== uploader ) {
            uploader.open();
            return;
        }

        uploader = wp.media.frames.file_frame = wp.media({
            frame:    'post',
            state:    'insert',
            multiple: multipleSelection
        });

        uploader.on( 'insert', function() {
            var selections = uploader.state().get( 'selection').toJSON();
            var picInfos = [];

            for(var sIdx = 0; sIdx < selections.length; sIdx++){
                var json = selections[sIdx];
                if ( 0 > jQuery.trim( json.url.length ) ) {
                    continue;
                }

                var picInfo = {};
                picInfo.id = json.id;
                picInfo.src = json.sizes.full.url;

                if(json.sizes.medium){
                    picInfo.src = json.sizes.medium.url;
                }

                picInfos.push(picInfo);
            }

            if(multipleSelection){
                console.log(picInfos);
                //callback(picInfos);
            }else{
                console.log('else');
                //callback(picInfos.length > 0 ? picInfos[0] : null);
            }
        });

        uploader.open();
    }

    const handleDocumentForm = function () {
        $('#documentUpload').on('change', function (e) {
            e.preventDefault();
            console.log(e);

            var files = $fileSelect.files;

            $.each(files, function(key, file){
                fileArray[key] = file;

                formData.append('files[]', file, file.name);
            });

            $.post(DP_AJAX_URL, {
                action: 'dp_document',
                data: formData
            }, function (response){
                if (response.status === 'success') {
                    toastr.success(response.message);
                    console.log(response);
                } else {
                    toastr.error(response.message);
                }
            })
            $documentForm.children('input').val('');
        });
    }

    $(document).ready(function() {
        pageInit();
    });

    // secret sauce method, thanks Paul Colella
    jQuery.fn.serializeObject = function () {
        let arrayData, objectData;
        arrayData = this.serializeArray();
        objectData = {};

        $.each(arrayData, function () {
            let value;

            if (this.value != null) {
                value = this.value;
            } else {
                value = '';
            }

            if (objectData[this.name] != null) {
                if (! objectData[this.name].push) {
                    objectData[this.name] = [objectData[this.name]];
                }

                objectData[this.name].push(value);
            } else {
                objectData[this.name] = value;
            }
        });

        return objectData;
    };
})(jQuery);