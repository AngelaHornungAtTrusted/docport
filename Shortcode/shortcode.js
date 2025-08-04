(function ($) {
    let $panel, $campaignSelect, $categorySelect, $platformSelect;

    const pageInit = function () {
        $campaignSelect = $('#dp-campaign-select');
        $categorySelect = $('#dp-category-select');
        $platformSelect = $('#dp-platform-select');

        getCamaigns();
        getCategories();
        getPlatforms();
    }

    /* Campaign Management */
    const getCamaigns = function () {
        $.get(DP_AJAX_URL, {
            action: 'dp_shortcode_campaign',
        }, function(response){
            if (response.status === 'success') {
                initCampaigns(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const initCampaigns = function (campaigns) {
        $.each(campaigns, function(key, campaign){
             $campaignSelect.append('<option class="campaign-select-option" id="campaign-' + campaign.id + '" value="' + campaign.id + '">' + campaign.title + '</option>');
        });

       $campaignSelect.off('change').on('change', function(e){
            CAMPAIGNID = e.currentTarget.value;
            documentQuery();
        });
    }

    /* Category Management */
    const getCategories = function (){
        $.get(DP_AJAX_URL, {
            action: 'dp_shortcode_category'
        }, function(response){
            if (response.status === 'success') {
                initCategories(response.data);
            } else {
                toastr.error(response.message);
            }
        })
    }

    const initCategories = function (categories) {
        $.each(categories, function(key, category){
            $categorySelect.append('<option class="category-select-optioin" id="category-' + category.id + '" value="' + category.id + '">' + category.title + '</option>');
        });

        $categorySelect.off('change').on('change', function(e){
            CATEOGRYID = e.currentTarget.value;
            documentQuery();
        });
    }

    /* Platform Management */
    const getPlatforms = function () {
        $.get(DP_AJAX_URL, {
            'action': 'dp_shortcode_platform'
        }, function(response){
            if (response.status === 'success') {
                initPlatforms(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const initPlatforms = function(platforms) {
        $.each(platforms, function(key, platform){
            $platformSelect.append('<option class="platform-select-option" id="platform-' + platform.id + '" value="' + platform.id + '">' + platform.title + '</option>')
        });

        $platformSelect.off('change').on('change', function(e) {
            PLATFORMID = e.currentTarget.value;
            documentQuery();
        });
    }

    /* Document Management */
    const documentQuery = function () {
        $.get(DP_AJAX_URL, {
            action: 'dp_shortcode_document',
            data: {
                'camId': CAMPAIGNID,
                'catId': CATEOGRYID,
                'platId': PLATFORMID
            }
        }, function(response){
            if (response.status === 'success') {
                console.log(response.data);
            } else {
                toastr.error(response.message);
            }
        })
    }

    $(document).ready(function(){
        pageInit();
    });
})(jQuery);