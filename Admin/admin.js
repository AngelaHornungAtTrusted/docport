(function($) {

    let $cForm, $dForm;

    const formInit = function() {
        //set form vars
        $cForm = $('#dp-cat-form');
        $dForm = $('#dp-doc-form');

        //call form handlers
        handleCategoryForm();
        handleDocumentForm();
    }

    const handleCategoryForm = function() {
        $cForm.validate({
            focusInvalid: false,
            rules: {},
            message: {},

            submitHandler: function (f, e) {
                e.preventDefault();
                console.log(e);
                let validator = this;
                let promise = $.ajax({
                    url: $cForm.prop('action'),
                    type: 'post',
                    data: $cForm.serializeObject(),
                }).done(function (response, s, r) {
                    if (typeof response.success !== 'undefined' && response.success) {
                        toastr.success('Success');
                    } else if (typeof response.success !== 'undefined' && response.errors.length) {
                        toastr.error(response.errors);
                    } else if (typeof response.success !== 'undefined' && response.message) {
                        toastr.error(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }).fail(function () {
                    toastr.error('Unknown Error');
                }).always(function () {
                });
            }
        });
    }

    const handleDocumentForm = function() {
        $dForm.validate({
            focusInvalid: false,
            rules: {},
            message: {},

            submitHandler: function (f, e) {
                e.preventDefault();
                console.log(e);
                let validator = this;
                let promise = $.ajax({
                    url: $dForm.prop('action'),
                    type: 'post',
                    data: $dForm.serializeObject(),
                }).done(function (response, s, r) {
                    if (typeof response.success !== 'undefined' && response.success) {
                        toastr.success('Success');
                    } else if (typeof response.success !== 'undefined' && response.errors.length) {
                        toastr.error(response.errors);
                    } else if (typeof response.success !== 'undefined' && response.message) {
                        toastr.error(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }).fail(function () {
                    toastr.error('Unknown Error');
                }).always(function () {
                });
            }
        });
    }

    $(document).ready(function() {
        formInit();
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