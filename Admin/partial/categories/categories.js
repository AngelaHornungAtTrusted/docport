(function($) {
    let $categoryTable, $categoryButton, $categoryForm;
    let checked;

    const pageInit = function() {
        console.log('Categories');

        //elements
        $categoryForm = $('#categoryForm');
        $categoryButton = $('#categoryButton');
        $categoryTable = $('#categoryTable');

        categoryCreate();
        getcategorys();
    }

    const getcategorys = function () {
        $.get(DP_AJAX_URL, {
                action: "dp_category",
            }, function (response) {
                if (response.status === 'success') {
                    //toastr.success(response.message);
                    categoryTableInit(response.data);
                } else {
                    toastr.error(response.message);
                }
            }
        );
    }

    const categoryTableInit = function (data) {
        //clear table
        $categoryTable[0].innerHTML = "";

        //set up table
        $.each(data, function(key, category){
            checked = ((parseInt(category.active) === 1) ? "Checked" : "");

            $categoryTable.append('<tr>' +
                '<td><input class="ctitle" type="text" id="category-title-' + category.id + '" value="' + category.title + '"></td>' +
                '<td><input class="cactive" type="checkbox" id="category-active-' + category.id + '" ' + checked + '></td>' +
                '<td><a class="button button-secondary category-copy" id="category-copy-' + category.id + '" style="background-color: orange; color: white;"><i class="fa fa-copy"></i></a>' +
                '<a class="button button-secondary category-delete" id="category-delete-' + category.id + '" style="background-color: red; color: white;"><i class="fa fa-trash danger"></i></a></td>' +
                '</tr>')
        });

        //reset actions
        $('.ctitle').off('change').on('change', categoryUpdate);
        $('.cactive').off('click').on('click', categoryUpdate);
        $('.category-copy').off('click').on('click', categoryCopy);
        $('.category-delete').off('click').on('click', categoryDelete);
    }

    const categoryUpdate = function (e) {
        $.post( DP_AJAX_URL, {
            action: "dp_category",
            data: {
                'categoryId': e.currentTarget.id.split('-')[2],
                'title': $('#category-title-' + e.currentTarget.id.split('-')[2]).val(),
                'active': $('#category-active-' + e.currentTarget.id.split('-')[2]).is(':checked')
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.messages);
            }
        })
    }

    const categoryCopy = function (e) {
        $.post(DP_AJAX_URL, {
            action: "dp_category",
            data: {
                'title': $('#category-title-' + e.currentTarget.id.split('-')[2]).val(),
                'active': $('#category-active-' + e.currentTarget.id.split('-')[2]).is(':checked')
            }
        }, function(response){
            if (response.status === 'success') {
                toastr.success(response.message);
                getcategorys();
            } else {
                toastr.error(response.message);
            }
        });
    }

    const categoryDelete = function (e) {
        $.post(DP_AJAX_URL, {
            action: "dp_category",
            data: {
                'categoryId': e.currentTarget.id.split('-')[2]
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                getcategorys();
            } else {
                toastr.error(response.message);
            }
        })
    }

    const categoryCreate = function () {
        $categoryButton.on('click', function (e) {
            e.preventDefault();

            $.post(DP_AJAX_URL, {
                action: "dp_category",
                data: {
                    'title': $('#ctitle').val(),
                    'active': $('#cactive').is(':checked')
                }
            }, function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $categoryForm.children('input').val('');
                    getcategorys();
                } else {
                    toastr.error(response.message);
                }
            })
        })
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