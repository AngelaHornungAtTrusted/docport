<?php
/*
 * Plugin Name: Docport
 * Description: Document Portfolio
 * Version: 1.0
 * Requires at least: 5.2
 * Requires PHP: 8.2.0
 * Author: Angela Hornung
 * Prefix: dp
 */

//load classes & configs
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DocportConfig.php');
require_once(DP_ROOT_DIR_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require_once(DP_UTIL_DIR_PATH . DIRECTORY_SEPARATOR . 'dp-ajax.php');
require_once(DP_UTIL_DIR_PATH . DIRECTORY_SEPARATOR .'dp-ajax-shortcode.php');

/* variables & objects */

use Util\DbTableManager;

/* Plugin Activation & Installation Management Hooks */
register_activation_hook(__FILE__, 'dp_activate');

/* Actions */
add_action('admin_menu', 'docport_menu');

//todo clean implementations of this feature
/*add_action('admin_post_dp_export_action', 'dp_export_data');
add_action('admin_footer', 'export_button');*/

/* Administrative Ajax Actions */
add_action('wp_ajax_dp_campaign', 'wp_ajax_dp_campaign');
add_action('wp_ajax_dp_category', 'wp_ajax_dp_category');
add_action('wp_ajax_dp_document', 'wp_ajax_dp_document');
add_action('wp_ajax_dp_platform', 'wp_ajax_dp_platform');
add_action('wp_ajax_dp_doc_cam', 'wp_ajax_dp_doc_cam');
add_action('wp_ajax_dp_doc_cat', 'wp_ajax_dp_doc_cat');
add_action('wp_ajax_dp_doc_plat', 'wp_ajax_dp_doc_plat');

/* Shortcode Ajax Actions */
add_action('wp_ajax_dp_shortcode_document', 'wp_ajax_dp_shortcode_document');
add_action('wp_ajax_dp_shortcode_filters', 'wp_ajax_dp_shortcode_filters');
add_action('wp_ajax_dp_shortcode_downloads', 'wp_ajax_dp_shortcode_downloads');
add_action('wp_ajax_nopriv_dp_shortcode_document', 'wp_ajax_dp_shortcode_document');
add_action('wp_ajax_nopriv_dp_shortcode_filters', 'wp_ajax_dp_shortcode_filters');
add_action('wp_ajax_nopriv_dp_shortcode_downloads', 'wp_ajax_dp_shortcode_downloads');

function dp_activate()
{
    try {
        //make sure tables exist, dbDelta makes sure there are no duplicates
        global $wpdb;

        $dpTableManager = new DbTableManager($wpdb);
        $dpTableManager->initTables();
        $dpTableManager->insertCategory('test');
    } catch (\Exception $e) {
        //todo implement cleaner and more proper error reporting
        var_dump($e->getMessage());
    }
}

function docport_menu()
{
    add_menu_page(
        'Docport Management', // Page title (for the admin panel)
        'Docport', // Menu title (what users see)
        'manage_options', // Required capability
        'docport-page', // Menu slug (unique identifier)
        'docport_page_content' // Callback function to display content
    );
}

function docport_page_content()
{
    global $wpdb;
    $dbTableManager = new DbTableManager($wpdb);
    ?>
    <div class="wrap">
        <?php wp_enqueue_style('bootstrap-css', DP_ASSETS_URL . '/bootstrap/css/bootstrap.css"'); ?>
        <?php wp_enqueue_script('bootstrap-js', DP_ASSETS_URL . '/bootstrap/js/bootstrap.js"'); ?>
        <?php include(plugin_dir_path(__FILE__) . 'Admin/admin.php'); ?>
    </div>
    <?php
}

add_shortcode('docport', 'docport_shortcode');

function docport_shortcode($atts = [], $content = null) {

    //make sure we have what we need
    if (sizeof($atts) > 2) {
        //used to determine what we load
        $campaignId = intval($atts[0]);
        $categoryId = intval($atts[1]);
        $platformId = intval($atts[2]);

        ?>
        <div class="wrap">
            <?php include(plugin_dir_path(__FILE__) . 'Shortcode/shortcode.php'); ?>
            <?php wp_enqueue_script('shortcode-js', plugin_dir_url(__FILE__) . 'Shortcode/shortcode.js'); ?>
            <?php wp_enqueue_style('bootstrap-css', DP_ASSETS_URL . '/bootstrap/css/bootstrap.css"'); ?>
            <?php wp_enqueue_script('bootstrap-js', DP_ASSETS_URL . '/bootstrap/js/bootstrap.js"'); ?>
            <?php wp_enqueue_script('toastr', plugin_dir_url(__FILE__) . 'Assets/toastr/toastr.js', array('jquery'));
            wp_enqueue_style('toastr', plugin_dir_url(__FILE__) . 'Assets/toastr/build/toastr.css'); ?>
        </div>
        <?php
    } else {
        ?>
        <div class="wrap">
            <h2>Missing Parameters</h2>
            <p>The shortcode template is as follows: [docport campaign_id category_id platform_id]</p>
            <p>If you want all campaigns, categories or platforms to present and an associated select for filtering, insert
            a zero for one of the ids.</p>
        </div>
<?php
    }
}

?>