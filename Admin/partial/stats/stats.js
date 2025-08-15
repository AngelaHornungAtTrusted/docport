(function($) {
    let $dayGraph, $downloadsTable, $documentSelect;
    let dates = [], docs = [], nums = {};
    let docId = 0, startDate = 0, endDate = 0;
    const pageInit = function() {
        $dayGraph = $('#day-graph');
        $downloadsTable = $('#downloadsTable');
        $documentSelect = $('#documentSelect');

        initialData();
    }

    const initialData = function() {
        $.get(DP_AJAX_URL, {
            action: 'dp_downloads',
        }, function(response){
            if (response.status === 'success') {
                //set up data arrays
                $.each(response.data[0], function(index, download){
                    if (!dates.includes(download.create_date)) {
                        dates.push(download.create_date);
                        nums[download.create_date] = 1;
                    } else {
                        nums[download.create_date]++;
                    }
                });

                //build initial charts
                buildDownloadTable(response.data[1]);
                buildDownloadChart();
            } else {
                toastr.error(response.message);
            }
        })
    }

    const buildDownloadChart = function() {
        new Chart($dayGraph, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Downloads Per Day',
                    data: nums,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    }

    const buildDownloadTable = function(data) {
        $.each(data, function(key, document){
            $downloadsTable.append('<tr>' +
                '<td>' + document.title + '</td>' +
                '<td>' + document.downloads + '</td>' +
                '<td><a class="btn btn-secondary" href="' + document.path + '" download><i class="fa fa-download" style="color: white;"></i></a></td>' +
                '</tr>');

            $documentSelect.append('<option value="' + document.id + '">' + document.title + '</option>');
        });

        initFilters(data);
    }

    const initFilters = function(data) {
        $documentSelect.off('change').on('change', function() {
            docId = $(this).val();

            filteredDataRequest();
        });
    }

    const filteredDataRequest = function() {
        console.log(docId);
        console.log(startDate);
        console.log(endDate);
    }

    $(document).ready(function() {
        pageInit();
    });
})(jQuery);