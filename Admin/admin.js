/*(function($) {

    let $cForm, $dForm, $cTable, $dTable;
    let checked, catId, catStatus, catTitle, docId, docStatus, docTitle, categories, catOptions;

    const pageInit = function() {
        //forms
        $cForm = $('#dp-cat-form');
        $dForm = $('#dp-doc-form');

        //elements
        $cTable = $('#dp-cat-table');
        $dTable = $('#dp-doc-table');

        //grab categories (grab docs after we have categories as those depend on the categories)
        grabCategories();

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
                categories = response.data.content;
                categoryTableInit(categories);
                grabDocuments();
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

        catCheckWatch();
        catInputWatch();
    }

    const catCheckWatch = function () {
        $('.dp-cat-checkbox').on('click', function(e){
            catId = e.currentTarget.value;
            catStatus = e.currentTarget.checked;

            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'dp-cat-id':catId,
                    'dp-cat-status':catStatus,
                    'dp-post-type':1            //0 is for new category, 1 is to update activity, 2 is for title
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

    const catInputWatch = function () {
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
        //clear table
        $dTable[0].innerHTML="";
        let promise = $.ajax({
            url: $dTable.data('loader'),
            type: 'GET',
            data: {},
        }).done(function (response) {
            if (response.data.success === 'success') {
                toastr.success(response.data.message);
                documentTableInit(response.data.content);
            } else {
                toastr.error(response.data.message);
            }
        }).always(function (response, s, r) {
        });
    }

    const documentTableInit = function (documents) {
        //set up select options html (standard across all)
        catOptions = '<option class="placeholder" value="0">Uncategorized</option>';
        $.each(categories, function (key, cat){
            console.log(cat.title);
            catOptions += '<option class="dp-doc-option" value="' + cat.id + '">' + cat.title + '</option>'
        });

        $.each(documents, function (key, doc) {
            checked = doc.active === '1' ? 'checked' : '';
            $dTable.append('' +
                '<tr>' +
                '<td><input class="dp-doc-title" id="doc-title-' + doc.id + '" type="text" value="' + doc.title + '"></td>' +
                '<td><select id="doc-select-' + doc.id + '" class="dp-doc-select">' + catOptions + '</select> </td>' +
                '<td><input class="dp-doc-checkbox" type="checkbox" id="doc-check-' + doc.id + '" value="' + doc.id + '" ' + checked + '></td>' +
                '</tr>');

            //select current document category
            $('#doc-select-' + doc.id).val(parseInt(doc.cat_id));
        });

        docCheckWatch();
        docTitleWatch();
        docSelectWatch();
    }

    const docCheckWatch = function () {
        $('.dp-doc-checkbox').on('click', function(e){
            docId = e.currentTarget.value;
            docStatus = e.currentTarget.checked;

            let promise = $.ajax({
                url: $dTable.data('loader'),
                type: 'POST',
                data: {
                    'dp-doc-id':docId,
                    'dp-doc-status':docStatus,
                    'dp-post-type':1            //0 is for new document, 1 is to update activity, 2 is for title
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

    const docTitleWatch = function () {
        $('.dp-doc-title').on('change', function(e){
            docId = e.currentTarget.id;
            docTitle = e.currentTarget.value;

            let promise = $.ajax({
                url: $dTable.data('loader'),
                type: 'POST',
                data: {
                    'dp-doc-id':docId.split('-')[2],
                    'dp-doc-title':docTitle,
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

    //todo implement category changing for documents
    const docSelectWatch = function () {
        $('.dp-doc-select').on('change', function(e){
            docId = e.currentTarget.id;
            catId = e.target.value;

            let promise = $.ajax({
                url: $dTable.data('loader'),
                type: 'POST',
                data: {
                    'dp-doc-id':docId.split('-')[2],
                    'dp-doc-cat':catId,
                    'dp-post-type':3            //0 is for new category, 1 is to update, 2 is for title, 3 is for new category
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

    const handleDocumentForm = function() {
        $dForm.on('submit', function(e){
            const files = document.querySelector('[type=file]').files
            const formData = new FormData()

            for (let i = 0; i < files.length; i++) {
                let file = files[i]
                formData.append('files[]', file)
            }
            e.preventDefault();
            fetch($dForm.prop('action'), {
                method: 'post',
                body: formData,
                //todo requires testing
                data: {
                    'dp-post-type':0            //0 is for new document, 1 is to update activity, 2 is for title
                },
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
})(jQuery);*/