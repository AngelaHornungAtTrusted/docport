(function($) {

    let $cForm, $dForm, $cTable;
    let checked, catId, catStatus;

    const pageInit = function() {
        //set page vars
        $cForm = $('#dp-cat-form');
        $dForm = $('#dp-doc-form');
        $cTable = $('#dp-cat-table');

        //grab categories
        grabCategories();
        //call form handlers
        handleCategoryForm();
        handleDocumentForm();
    }

    const grabCategories = function () {
        let promise = $.ajax({
            url: $cTable.data('loader'),
            type: 'GET',
            data: {},
        }).done(function (response) {
            console.log(response.data);
            if (response.data.success === 'success') {
                toastr.success(response.data.message);
                categoryTableInit(response.data.content);
            } else {
                toastr.error(response.data.message);
            }
        }).always(function (response, s, r) {
        });
    }

    const categoryTableInit = function (categories) {
        $.each(categories, function (key, cat) {
            checked = cat.active === '1' ? 'checked' : '';
            $cTable.append('' +
                '<tr>' +
                '<td>' + cat.title + '</td>' +
                '<td><input class="dp-cat-checkbox" type="checkbox" id="cat-check-' + cat.id + '" value="' + cat.id + '" ' + checked + '></td>' +
                '</tr>');
        });

        checkWatch();
    }

    const checkWatch = function () {
        $('.dp-cat-checkbox').on('click', function(e){
            catId = e.currentTarget.value;
            catStatus = e.currentTarget.checked;

            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'dp-cat-id':catId,
                    'dp-cat-status':catStatus,
                    'dp-post-type':1            //0 is for new category, 1 is to update
                },
            }).done(function (response) {
                console.log(response.data);
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
    }

    const handleCategoryForm = function() {
        $cForm.validate({
            focusInvalid: false,
            rules: {},
            message: {},

            submitHandler: function (f, e) {
                e.preventDefault();
                let validator = this;
                let promise = $.ajax({
                    url: $cForm.prop('action'),
                    type: 'post',
                    data: $cForm.serializeObject(),
                }).done(function (response, s, r) {
                    if (s === 'success') {
                        toastr.success('Success');
                    } else {
                        toastr.error('Error');
                    }
                }).fail(function () {
                    toastr.error('Unknown Error');
                }).always(function () {
                    $cForm.children('input').val('');
                });
            }
        });
    }

    const handleDocumentForm = function() {
        //todo implement document handling
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