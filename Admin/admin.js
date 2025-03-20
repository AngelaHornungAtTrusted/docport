(function($) {

    let $cForm, $dForm, $cTable, $dTable;
    let checked, catId, catStatus;

    const pageInit = function() {
        //forms
        $cForm = $('#dp-cat-form');
        $dForm = $('#dp-doc-form');

        //elements
        $cTable = $('#dp-cat-table');

        //grab categories
        grabCategories();
        //grab documents
        grabDocuments();

        //call form handlers
        handleCategoryForm();
        handleDocumentForm();
    }

    const grabCategories = function () {
        //clear table
        $cTable[0].innerHTML="";
        let promise = $.ajax({
            url: $cTable.data('loader'),
            type: 'GET',
            data: {},
        }).done(function (response) {
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
                '<td><input class="dp-cat-title" id="cat-title-' + cat.id + '" type="text" value="' + cat.title + '"></td>' +
                '<td><input class="dp-cat-checkbox" type="checkbox" id="cat-check-' + cat.id + '" value="' + cat.id + '" ' + checked + '></td>' +
                '</tr>');
        });

        inputWatch();
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
                    'dp-post-type':1            //0 is for new category, 1 is to update, 2 is for title
                },
            }).done(function (response) {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
    }

    const inputWatch = function () {
        $('.dp-cat-title').on('change', function(e){
            catId = e.currentTarget.id;
            catTitle = e.currentTarget.value;

            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'dp-cat-id':catId.split('-')[2],
                    'dp-cat-title':catTitle,
                    'dp-post-type':2            //0 is for new category, 1 is to update, 2 is for title
                },
            }).done(function (response) {
                console.log(response);
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
                }).done(function (response) {
                    if (response.data.success === 'success') {
                        toastr.success(response.data.message);
                        grabCategories();
                    } else {
                        toastr.error(response.data.message);
                    }
                }).fail(function () {
                    toastr.error('Unknown Error');
                }).always(function () {
                    $cForm.children('input').val('');
                });
            }
        });
    }

    const grabDocuments = function () {

    }

    const handleDocumentForm = function() {
        $dForm.on('submit', function(e){
            const files = document.querySelector('[type=file]').files
            const formData = new FormData()

            for (let i = 0; i < files.length; i++) {
                let file = files[i]
                console.log(file);

                formData.append('files[]', file)
            }
            e.preventDefault();
            fetch($dForm.prop('action'), {
                method: 'post',
                body: formData,
            }).then((response) => {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            });
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