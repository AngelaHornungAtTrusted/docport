(function ($) {
    let $fileSelect, $documentTable;
    let campaigns, categories, platforms;

    const pageInit = function () {
        $fileSelect = $('#documentUpload');
        $documentTable = $('#documentTable');

        $fileSelect.on('click', openMediaUploader);

        //get select variables
        getCampaigns();
        getCategories();
        getPlatforms();

        //get documents and associations
        getDocuments();
    }

    const getCampaigns = function () {
        //grab campaigns list
        $.get(DP_AJAX_URL, {
            action: 'dp_campaign'
        }, function (response) {
            if (response.status === 'success') {
                campaigns = response.data;
            } else {
                toastr.error(response.message);
            }
        });
    }

    const getCategories = function () {
        //grab categories list
        $.get(DP_AJAX_URL, {
            action: 'dp_category'
        }, function (response) {
            if (response.status === 'success') {
                categories = response.data;
            } else {
                toastr.error(response.message);
            }
        });
    }

    const getPlatforms = function () {
        $.get(DP_AJAX_URL, {
            action: 'dp_platform'
        }, function (response) {
            if (response.status === 'success') {
                platforms = response.data;
            } else {
                toastr.error(response.message);
            }
        })
    }

    const getDocuments = function () {
        $.get(DP_AJAX_URL, {
            action: 'dp_document',
        }, function (response) {
            if (response.status === 'success') {
                //set up document table
                documentPanelInit(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const documentPanelInit = function (data) {
        //todo find more efficient manner of doing this
        $.each(data.documents, function (key, document) {
            //set document row
            $documentTable.append('<tr id="document-row-' + document.id + '">' +
                '<td><input id="document-title-' + document.id + '" type="text" value="' + document.title + '"></td>' +
                '<td><div class="dropdown"><button class="document-campaign-select" id="document-campaign-' + document.id + '">Select Campaigns</button><div class="options-campaigns dropdown-content campaigns-' + document.id + '"></div></div></td>' +
                '<td><div class="dropdown"><button class="document-category-select" id="document-category-' + document.id + '">Select Categories</button><div class="options-categories dropdown-content categories-' + document.id + '"></div></div></td>' +
                '<td><div class="dropdown"><button class="document-platform-select" id="document-platform-' + document.id + '">Select Platforms</button><div class="options-platforms dropdown-content platforms-' + document.id + '"></div></div></td>' +
                '<td><a class="btn btn-danger document-delete" id="document-delete-' + document.id + '" style="background-color: red; color: white;"><i class="fa fa-trash"></i></a>' +
                '<a class="btn btn-secondary document-thumbnail" id="document-thumbnail-' + document.id + '"><i class="fa fa-image"></i></a></td>' +
                '</tr>');

            /* todo append options once, run through documents twice to set selects, should be more efficient */
            $.each(campaigns, function (key, campaign) {
                $('.campaigns-' + document.id).append('<label for="campaign-option-' + campaign.id + document.id + '">' + campaign.title + '</label><input class="campaign-option" type="checkbox" id="campaign-option-' + campaign.id + document.id + '" value="' + campaign.id + '">');
            });

            $.each(categories, function (key, category) {
                $('.categories-' + document.id).append('<label for="category-option-' + category.id + document.id + '">' + category.title + '</label><input class="category-option" type="checkbox" id="category-option-' + category.id + document.id + '" value="' + category.id + '">');
            });

            $.each(platforms, function (key, platform) {
                $('.platforms-' + document.id).append('<label for="platform-option-' + platform.id + document.id + '">' + platform.title + '</label><input class="platform-option" type="checkbox" id="platform-option-' + platform.id + document.id + '" value="' + platform.id + '">');
            });

            $.each(data.cams, function(key, cam){
                if (cam.doc_id === document.id) {
                    $('#campaign-option-' + cam.cam_id + document.id).attr('checked', true);
                }
            });

            $.each(data.cats, function(key, cat){
                if (cat.doc_id === document.id) {
                    $('#category-option-' + cat.cat_id + document.id).attr('checked', true);
                }
            });

            $.each(data.plats, function(key, plat) {
                if (plat.doc_id === document.id) {
                    $('#platform-option-' + plat.plat_id + document.id).attr('checked', true);
                }
            })
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
        });

        $('.document-thumbnail').off('click').on('click', openMediaUploader);

        documentDeleteInit();
    }

    const documentCampaignUpdate = function (e) {
        $.post(DP_AJAX_URL, {
            action: 'dp_doc_cam',
            data: {
                camId: e.currentTarget.value,
                docId: e.target.parentElement.classList[2].split('-')[1],
                checked: e.currentTarget.checked
            }
        }, function (response){
            if (response.status === 'success') {

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
                docId: e.target.parentElement.classList[2].split('-')[1],
                checked: e.currentTarget.checked
            }
        }, function (response) {
            if (response.status === 'success') {

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
                docId: e.target.parentElement.classList[2].split('-')[1],
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
            $.post(DP_AJAX_URL, {
                action: 'dp_document',
                data: {
                    id: e.currentTarget.id.split('-')[2]
                }
            }, function (response){
                if (response.status === 'success') {
                    $('#document-row-' + e.currentTarget.id.split('-')[2]).remove();
                } else {
                    toastr.error(response.message);
                }
            })
        })
    }

    const openMediaUploader = function (e) {
        'use strict';

        let mode = (e.currentTarget.id === 'documentUpload') ? 'document' : 'image';
        console.log(e.currentTarget.id);
        console.log(mode);

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
                    if (response.status === 'success') {
                        location.reload();
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