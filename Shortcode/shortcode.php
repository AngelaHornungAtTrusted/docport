<?php

?>
<script>
    DP_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
    CAMPAIGNID = '<?php echo $campaignId; ?>';
    CATEGORYID = '<?php echo $categoryId; ?>';
    PLATFORMID = '<?php echo $platformId; ?>';
</script>
<div class="row">
    <div class="row col-md-12">
        <?php
        if ($campaignId == 0) {
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="dp-campaign-select" hidden>Select
                        Campaign</label>
                    <select id="dp-campaign-select" class="dp-select">
                        <option selected value="0">Select Campaign</option>
                    </select>
                </div>
            </div>
            <?php
        }

        if ($categoryId == 0) {
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <!-- if zero grab all & display, otherwise set value and hide element -->
                    <label for="dp-category-select" hidden>Select
                        Category</label>
                    <select id="dp-category-select" class="dp-select">
                        <option selected value="0">Select Category</option>
                    </select>
                </div>
            </div>
            <?php
        }

        if ($platformId == 0) {
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <!-- if zero grab all & display, otherwise set value and hide element -->
                    <label for="dp-platform-select" hidden>Select
                        Platform</label>
                    <select id="dp-platform-select" class="dp-select">
                        <option selected value="0">Select Platform</option>
                    </select>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="row col-md-12" id="dp-document-panel">

    </div>
</div>
