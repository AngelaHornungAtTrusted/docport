<?php

?>
<script>
    DP_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
    CAMPAIGNID = '<?php echo $campaignId; ?>';
    CATEOGRYID = '<?php echo $categoryId; ?>';
    PLATFORMID = '<?php echo $platformId; ?>';
</script>
<div class="pp-product-panel">
    <div class="row col-md-12">
        <?php
        if ($campaignId == 0) {
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pp-campaign-select">Select
                        Campaign</label>
                    <select id="pp-campaign-select"></select>
                </div>
            </div>
            <?php
        }

        if ($categoryId == 0) {
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <!-- if zero grab all & display, otherwise set value and hide element -->
                    <label for="pp-cateogry-select">Select
                        Category</label>
                    <select id="pp-cateogry-select"></select>
                </div>
            </div>
            <?php
        }

        if ($platformId == 0) {
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <!-- if zero grab all & display, otherwise set value and hide element -->
                    <label for="pp-platform-select">Select
                        Platform</label>
                    <select id="pp-platform-select"></select>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
