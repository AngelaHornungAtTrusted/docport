(function($) {
    let $platformTable, $platformButton, $platformForm;
    let checked;

    const pageInit = function() {
        //elements
        $platformForm = $('#platformForm');
        $platformButton = $('#platformButton');
        $platformTable = $('#platformTable');

        platformCreate();
        getplatforms();
    }

    const getplatforms = function () {
        $.get(DP_AJAX_URL, {
                action: "dp_platform",
            }, function (response) {
                if (response.status === 'success') {
                    //toastr.success(response.message);
                    platformTableInit(response.data);
                } else {
                    toastr.error(response.message);
                }
            }
        );
    }

    const platformTableInit = function (data) {
        //clear table
        $platformTable[0].innerHTML = "";

        //set up table
        $.each(data, function(key, platform){
            checked = ((parseInt(platform.active) === 1) ? "Checked" : "");

            $platformTable.append('<tr>' +
                '<td><input class="ctitle" type="text" id="platform-title-' + platform.id + '" value="' + platform.title + '"></td>' +
                '<td><input class="cactive" type="checkbox" id="platform-active-' + platform.id + '" ' + checked + '></td>' +
                '<td><a class="button button-secondary platform-copy" id="platform-copy-' + platform.id + '" style="background-color: orange; color: white;"><i class="fa fa-copy"></i></a>' +
                '<a class="button button-secondary platform-delete" id="platform-delete-' + platform.id + '" style="background-color: red; color: white;"><i class="fa fa-trash danger"></i></a></td>' +
                '</tr>')
        });

        //reset actions
        $('.ctitle').off('change').on('change', platformUpdate);
        $('.cactive').off('click').on('click', platformUpdate);
        $('.platform-copy').off('click').on('click', platformCopy);
        $('.platform-delete').off('click').on('click', platformDelete);
    }

    const platformUpdate = function (e) {
        $.post( DP_AJAX_URL, {
            action: "dp_platform",
            data: {
                'platformId': e.currentTarget.id.split('-')[2],
                'title': $('#platform-title-' + e.currentTarget.id.split('-')[2]).val(),
                'active': $('#platform-active-' + e.currentTarget.id.split('-')[2]).is(':checked')
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.messages);
            }
        })
    }

    const platformCopy = function (e) {
        $.post(DP_AJAX_URL, {
            action: "dp_platform",
            data: {
                'title': $('#platform-title-' + e.currentTarget.id.split('-')[2]).val(),
                'active': $('#platform-active-' + e.currentTarget.id.split('-')[2]).is(':checked')
            }
        }, function(response){
            if (response.status === 'success') {
                toastr.success(response.message);
                getplatforms();
            } else {
                toastr.error(response.message);
            }
        });
    }

    const platformDelete = function (e) {
        $.post(DP_AJAX_URL, {
            action: "dp_platform",
            data: {
                'platformId': e.currentTarget.id.split('-')[2]
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                getplatforms();
            } else {
                toastr.error(response.message);
            }
        })
    }

    const platformCreate = function () {
        $platformButton.on('click', function (e) {
            e.preventDefault();

            $.post(DP_AJAX_URL, {
                action: "dp_platform",
                data: {
                    'title': $('#ptitle').val(),
                    'active': $('#pactive').is(':checked')
                }
            }, function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $platformForm.children('input').val('');
                    getplatforms();
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