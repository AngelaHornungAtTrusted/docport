(function ($) {
    let $fileSelect, $documentTable;
    let campaigns, categories;

    const pageInit = function () {
        $fileSelect = $('#documentUpload');
        $documentTable = $('#documentTable');

        $fileSelect.on('click', openMediaUploader);

        getCampaigns();
    }

    const getCampaigns = function () {
        //grab campaigns list
        $.get(DP_AJAX_URL, {
            action: 'dp_campaign'
        }, function (response) {
            if (response.status === 'success') {
                getCategories(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const getCategories = function (campaigns) {
        //grab categories list
        $.get(DP_AJAX_URL, {
            action: 'dp_category'
        }, function (response) {
            if (response.status === 'success') {
                getPlatforms(campaigns, response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const getPlatforms = function (campaigns, categories) {
        $.get(DP_AJAX_URL, {
            action: 'dp_platform'
        }, function (response) {
            if (response.status === 'success') {
                getDocuments(campaigns, categories, response.data)
            } else {
                toastr.error(response.message);
            }
        })
    }

    const getDocuments = function (campaigns, categories, platforms) {
        $.get(DP_AJAX_URL, {
            action: 'dp_document',
        }, function (response) {
            if (response.status === 'success') {
                documentPanelInit(response.data, campaigns, categories, platforms);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const documentPanelInit = function (data, campaigns, categories, platforms) {

        $.each(data, function (key, document) {
            //set document row
            $documentTable.append('<tr>' +
                '<td><input id="document-title-' + document.id + '" type="text" value="' + document.title + '"></td>' +
                '<td><div class="dropdown"><button class="document-campaign-select" id="document-campaign-' + document.id + '">Select Campaigns</button><div class="options-campaigns dropdown-content campaigns-' + document.id + '"></div></div></td>' +
                '<td><div class="dropdown"><button class="document-category-select" id="document-category-' + document.id + '">Select Categories</button><div class="options-categories dropdown-content categories-' + document.id + '"></div></div></td>' +
                '<td><div class="dropdown"><button class="document-platform-select" id="document-platform-' + document.id + '">Select Platforms</button><div class="options-platforms dropdown-content platforms-' + document.id + '"></div></div></td>' +
                '<td><a class="btn btn-danger document-delete" id="document-delete-' + document.id + '" style="background-color: red; color: white;"><i class="fa fa-trash"></i></a></td>' +
                '</tr>');

            //set campaign options (done here so we have document id in option which is easier to grab on click)
            $.each(campaigns, function (key, campaign) {
                $('.campaigns-' + document.id).append('<label for="campaign-option-' + campaign.id + '">' + campaign.title + '</label><input class="campaign-option" type="checkbox" id="campaign-option-' + document.id + '" value="' + campaign.id + '">')
            });

            //set category options (done here so we have document id in option which is easier to grab on click)
            $.each(categories, function (key, category) {
                $('.categories-' + document.id).append('<label for="campaign-option-' + category.id + '">' + category.title + '</label><input class="category-option" type="checkbox" id="category-option-' + document.id + '" value="' + category.id + '">')
            });

            //set platform options (done here so we have document id in option which is easier to grab on click)
            $.each(platforms, function (key, platform) {
                $('.platforms-' + document.id).append('<label for="platform-option-' + platform.id + '">' + platform.title + '</label><input class="platform-option" type="checkbox" id="platform-option-' + document.id + '" value="' + platform.id + '">');
            });
        });

        panelActionsInit();
    }

    const panelActionsInit = function () {
        //set toggle
        $('.document-campaign-select').off('click').on('click', function (e) {
            let id = e.currentTarget.id.split('-')[2];
            $('.campaigns-' + id).toggle('show');

            $('.campaign-option').off('click').on('click', documentCampaignUpdate);
        });

        $('.document-category-select').off('click').on('click', function (e) {
            let id = e.currentTarget.id.split('-')[2];
            $('.categories-' + id).toggle('show');

            $('.category-option').off('click').on('click', documentCategoryUpdate);
        });

        $('.document-platform-select').off('click').on('click', function(e) {
            let id = e.currentTarget.id.split('-')[2];
            $('.platforms-' + id).toggle('show');

            $('.platform-option').off('click').on('click', documentPlatformUpdate);
        })

        documentDeleteInit();
    }

    const documentCampaignUpdate = function (e) {
        $.post(DP_AJAX_URL, {
            action: 'dp_doc_cam',
            data: {
                camId: e.currentTarget.value,
                docId: e.currentTarget.id.split('-')[2],
                checked: e.currentTarget.checked
            }
        }, function (response){
            if (response.status === 'success') {
                console.log(response.message);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const documentCategoryUpdate = function (e) {
        $.post(DP_AJAX_URL, {
            action: 'dp_doc_cat',
            data: {
                catId: e.currentTarget.value,
                docId: e.currentTarget.id.split('-')[2],
                checked: e.currentTarget.checked
            }
        }, function (response) {
            if (response.status === 'success') {
                console.log(response.message);
            } else {
                toastr.error(response.message);
            }
        })
    }

    const documentPlatformUpdate = function (e) {
        $.post(DP_AJAX_URL, {
            action: 'dp_doc_plat',
            data: {
                platId: e.currentTarget.value,
                docId: e.currentTarget.id.split('-')[2],
                checked: e.currentTarget.checked
            }
        }, function (response){
            if (response.status === 'success') {

            } else {
                toastr.error(response.message);
            }
        })
    }

    const documentDeleteInit = function () {
        $('.document-delete').off('click').on('click', function (e) {
            console.log(e.currentTarget.id);
        })
    }

    const openMediaUploader = function () {
        'use strict';

        var uploader;

        uploader = wp.media.frames.file_frame = wp.media({
            frame: 'post',
            state: 'insert',
            multiple: true
        });

        uploader.on('insert', function () {
            var selections = uploader.state().get('selection').toJSON();

            $.each(selections, function (key, selection) {
                console.log(selection);
                $.post(DP_AJAX_URL, {
                    action: 'dp_document',
                    data: {
                        'title': selection.title,
                        'path': selection.url,
                        'thumbnail': selection.icon
                    }
                }, function (response) {
                    if (response === 'success') {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                })
            });
        });

        uploader.open();
    }

    $(document).ready(function () {
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
                if (!objectData[this.name].push) {
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