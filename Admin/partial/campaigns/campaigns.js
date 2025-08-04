(function($) {
    let $campaignTable, $campaignButton, $campaignForm;
    let checked;

    const pageInit = function() {
        //elements
        $campaignForm = $('#campaignForm');
        $campaignButton = $('#campaignButton');
        $campaignTable = $('#campaignTable');

        campaignCreate();
        getCampaigns();
    }

    const getCampaigns = function () {
        $.get(DP_AJAX_URL, {
                action: "dp_campaign",
            }, function (response) {
                if (response.status === 'success') {
                    //toastr.success(response.message);
                    campaignTableInit(response.data);
                } else {
                    toastr.error(response.message);
                }
            }
        );
    }

    const campaignTableInit = function (data) {
        console.log('campaingTableInit');

        //clear table
        $campaignTable[0].innerHTML = "";

        //set up table
        $.each(data, function(key, campaign){
            checked = ((parseInt(campaign.active) === 1) ? "Checked" : "");

            $campaignTable.append('<tr>' +
                '<td><input class="ctitle" type="text" id="campaign-title-' + campaign.id + '" value="' + campaign.title + '"></td>' +
                '<td><input class="cmactive" type="checkbox" id="campaign-active-' + campaign.id + '" ' + checked + '></td>' +
                '<td><a class="button button-secondary campaign-copy" id="campaign-copy-' + campaign.id + '" style="background-color: orange; color: white;"><i class="fa fa-copy"></i></a>' +
                '<a class="button button-secondary campaign-delete" id="campaign-delete-' + campaign.id + '" style="background-color: red; color: white;"><i class="fa fa-trash danger"></i></a></td>' +
                '</tr>')
        });

        //reset actions
        $('.ctitle').off('change').on('change', campaignUpdate);
        $('.cmactive').off('click').on('click', campaignUpdate);
        $('.campaign-copy').off('click').on('click', campaignCopy);
        $('.campaign-delete').off('click').on('click', campaignDelete);
    }

    const campaignUpdate = function (e) {
        console.log('campaignUpdate');
        $.post(DP_AJAX_URL, {
            action: "dp_campaign",
            data: {
                'campaignId': e.currentTarget.id.split('-')[2],
                'title': $('#campaign-title-' + e.currentTarget.id.split('-')[2]).val(),
                'active': $('#campaign-active-' + e.currentTarget.id.split('-')[2]).is(':checked')
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.messages);
            }
        })
    }

    const campaignCopy = function (e) {
        $.post(DP_AJAX_URL, {
            action: "dp_campaign",
            data: {
                'title': $('#campaign-title-' + e.currentTarget.id.split('-')[2]).val(),
                'active': $('#campaign-active-' + e.currentTarget.id.split('-')[2]).is(':checked')
            }
        }, function(response){
            if (response.status === 'success') {
                toastr.success(response.message);
                getCampaigns();
            } else {
                toastr.error(response.message);
            }
        });
    }

    const campaignDelete = function (e) {
        $.post(DP_AJAX_URL, {
            action: "dp_campaign",
            data: {
                'campaignId': e.currentTarget.id.split('-')[2]
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                getCampaigns();
            } else {
                toastr.error(response.message);
            }
        })
    }

    const campaignCreate = function () {
        $campaignButton.on('click', function (e) {
            e.preventDefault();

            $.post(DP_AJAX_URL, {
                action: "dp_campaign",
                data: {
                    'title': $('#title').val(),
                    'active': $('#active').is(':checked')
                }
            }, function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $campaignForm.children('input').val('');
                    getCampaigns();
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