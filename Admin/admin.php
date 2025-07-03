<?php

?>
<script>
    DP_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
</script>
<h2>Docport Administration</h2>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <!--<li class="nav-item" role="presentation">
        <button class="nav-link active" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab" aria-controls="home" aria-selected="true">Statistics</button>
    </li>-->
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="campaigns-tab" data-bs-toggle="tab" data-bs-target="#campaigns"
                type="button" role="tab" aria-controls="profile" aria-selected="true">Campaigns
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button"
                role="tab" aria-controls="categories" aria-selected="false">Categories
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button"
                role="tab" aria-controls="documents" aria-selected="false">Documents
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="platforms-tab" data-bs-toggle="tab" data-bs-target="#platforms" type="button"
                role="tab" aria-controls="platforms" aria-selected="false">Platforms
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="organization-tab" data-bs-toggle="tab" data-bs-target="#oranization" type="button"
                role="tab" aria-controls="documents" aria-selected="false">Organization
        </button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <!--<div class="tab-pane fade show active" id="stats" role="tabpanel" aria-labelledby="stats-tab">
        <div class="wrap">
            <?php //include( plugin_dir_path( __FILE__ ) . 'partial/stats/stats.php' ); ?>
            <?php //wp_enqueue_script('stats-js', DP_ADMIN_URL . '/partial/stats/stats.js"', array('jquery')); ?>
        </div>
    </div>-->
    <div class="tab-pane fade" id="campaigns" role="tabpanel" aria-labelledby="campaigns-tab">
        <div class="wrap">
            <?php include(plugin_dir_path(__FILE__) . 'partial/campaigns/campaigns.php'); ?>
            <?php wp_enqueue_script('campaigns-js', DP_ADMIN_URL . '/partial/campaigns/campaigns.js"', array('jquery')); ?>
        </div>
    </div>
    <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
        <div class="wrap">
            <?php include(plugin_dir_path(__FILE__) . 'partial/categories/categories.php'); ?>
            <?php wp_enqueue_script('categories-js', DP_ADMIN_URL . '/partial/categories/categories.js"', array('jquery')); ?>
        </div>
    </div>
    <div class="tab-pane fade show active" id="documents" role="tabpanel" aria-labelledby="documents-tab">
        <div class="wrap">
            <?php include(plugin_dir_path(__FILE__) . 'partial/documents/documents.php'); ?>
            <?php wp_enqueue_script('documents-js', DP_ADMIN_URL . '/partial/documents/documents.js"', array('jquery')); ?>
        </div>
    </div>
    <div class="tab-pane fade" id="platforms" role="tabpanel" aria-labelledby="platforms-tab">
        <div class="wrap">
            <?php include(plugin_dir_path(__FILE__) . 'partial/platforms/platforms.php'); ?>
            <?php wp_enqueue_script('platforms-js', DP_ADMIN_URL . '/partial/platforms/platforms.js"', array('jquery')); ?>
        </div>
    </div>
</div>
