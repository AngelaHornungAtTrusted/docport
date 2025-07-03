(function($) {
    let $documentForm;
    const pageInit = function() {
        $documentForm = $('#documentForm');

        handleDocumentForm()
    }

    const handleDocumentForm = function () {
        console.log('handleDocumentForm');
        $('#documentUpload').on('change', function (e) {
            e.preventDefault();
            console.log(e);

            $.post(DP_AJAX_URL, {
                action: 'dp_document',
                data: $('#documentUpload').files
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