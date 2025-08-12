(function ($) {
    let $panel, $campaignSelect, $categorySelect, $platformSelect;

    const pageInit = function () {
        $campaignSelect = $('#dp-campaign-select');
        $categorySelect = $('#dp-category-select');
        $platformSelect = $('#dp-platform-select');
        $panel = $('#dp-document-panel');

        documentQuery();
        getFilters();
    }

    /* Campaign Management */
    const getFilters = function () {
        $.get(DP_AJAX_URL, {
            action: 'dp_shortcode_filters',
            data: {
                'camId': CAMPAIGNID,
                'catId': CATEGORYID,
                'platId': PLATFORMID
            }
        }, function(response){
            if (response.status === 'success') {
                initFilters(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const initFilters = function (filters) {
        $.each(filters, function(key, filterArray){
            $.each(filterArray, function(fKey, filter){
                switch (key) {
                    case 'campaigns':
                        if (CAMPAIGNID == 0) {
                            $campaignSelect.append('<option class="campaign-select-option" id="campaign-' + filter.cam_id + '" value="' + filter.cam_id + '">' + filter.title + '</option>');
                        }
                        break;
                    case 'categories':
                        if (CATEGORYID == 0) {
                            $categorySelect.append('<option class="category-select-option" id="category-' + filter.cat_id + '" value="' + filter.cat_id + '">' + filter.title + '</option>');
                        }
                        break;
                    case 'platforms':
                        if (PLATFORMID == 0) {
                            $platformSelect.append('<option class="platform-select-option" id="platform-' + filter.plat_id + '" value="' + filter.plat_id + '">' + filter.title + '</option>');
                        }
                        break;
                    default:
                        break;
                }
            });
        });

       $('.dp-select').off('change').on('change', function(e){
           switch (e.currentTarget.id.split('-')[1]) {
               case 'campaign':
                   CAMPAIGNID = e.currentTarget.value;
                   break;
               case 'category':
                   CATEGORYID = $(this).val();
                   break;
               case 'platform':
                   PLATFORMID = $(this).val();
                   break;
               default:
                   break;
           }
            documentQuery();
        });
    }

    /* Document Management */
    const documentQuery = function () {
        $.get(DP_AJAX_URL, {
            action: 'dp_shortcode_document',
            data: {
                'camId': CAMPAIGNID,
                'catId': CATEGORYID,
                'platId': PLATFORMID
            }
        }, function(response){
            if (response.status === 'success') {
                console.log(response.data);
                documentInit(response.data);
            } else {
                toastr.error(response.message);
            }
        })
    }

    const documentInit = function (documents) {
        $panel.empty();
        $.each(documents, function(key, document){
            $panel.append('<div class="col-md-3" style="border-right: 1px; border-color: black; border-style: solid;">' +
                '<a href="' + document.path + '" download>' +
                '<img class="center" src="' + document.thumbnail + '" style="min-width: 100%; max-height: 100px">' +
                '</a></br>' +
                '<div class="container" style="width: 100%; text-align: center;">' +
                '<a class="h6" href="' + document.path + '" download>' + document.title + '</a>' +
                '</div>' +
                '</div>')
        });
    }

    $(document).ready(function(){
        pageInit();
    });
})(jQuery);