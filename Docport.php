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

/* variables & objects */
require_once(__DIR__ . '/Util/Db/DbTableManager.php');

/* Plugin Activation & Installation Management Hooks */
register_activation_hook(__FILE__, 'dp_activate');
register_deactivation_hook(__FILE__, 'dp_deactivate');

/* Actions */
add_action('admin_menu', 'docport_menu');
add_action('admin_post_dp_export_action', 'dp_export_data');
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_admin_scripts');
add_action('admin_footer', 'export_button');

function dp_activate() {
	try{
		//make sure tables exist, dbDelta makes sure there are no duplicates
		$dpTableManager = new DbTableManager();
		$dpTableManager->initTables();
	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

function dp_deactivate() {

}

function docport_menu() {
	add_menu_page(
		'Docport Management', // Page title (for the admin panel)
		'Docport', // Menu title (what users see)
		'manage_options', // Required capability
		'docport-page', // Menu slug (unique identifier)
		'docport_page_content' // Callback function to display content
	);
}

function docport_page_content() {
    ?>
    <div class="wrap">
		<?php include( plugin_dir_path( __FILE__ ) . 'Admin/admin.phtml' ); ?>
    </div>
	<?php
}

//exports table data, called by export button
function dp_export_data() {
	try{
		$dpTableManager = new DbTableManager();
		$dpTableManager->exportTables();
		exit;
	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

//sets up export url for export button and admin.js
function my_plugin_enqueue_admin_scripts($hook): void {

	wp_enqueue_script(
		'your-plugin-admin-script', // Unique handle for the script
		plugin_dir_url( __FILE__ ) . 'Admin/admin.js', // Path to your script file
		array( 'jquery' ), // Dependencies (e.g., jQuery)
		'1.0', // Version number (optional, but recommended for cache busting)
		false // Load in the footer (true) or header (false)
	);

	//plugins page specific javascript
    if ('plugins.php' === $hook) {
	    //wp_enqueue_style('my-plugin-admin-style', plugin_dir_url(__FILE__) . 'css/admin.css');
	    wp_enqueue_script('my-plugin-admin-script', plugin_dir_url(__FILE__) . '', array('jquery'), '1.0', true);
	    wp_localize_script('my-plugin-admin-script', 'my_plugin_vars', array(
		    'nonce' => wp_create_nonce('my_plugin_custom_action'),
		    'action' => 'my_plugin_custom_action',
		    'export_url' => admin_url('admin-post.php?action=dp_export_action') // Add the URL here
	    ));
	}
}

//export button
function export_button(): void {
	$screen = get_current_screen();
	if ($screen->id !== 'plugins') {
		return;
	}
	?>
	<script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#deactivate-docport').each(function() {
                //Find deactivate button
                var targetButton = $('#deactivate-docport');

                if (targetButton) {
                    var pluginRow = $(this).closest('tr');
                    var pluginSlug = pluginRow.find('.plugin-title strong').text().toLowerCase().replace(/ /g, '-'); // Get plugin slug

                    var buttonHTML = '<a class="" style="margin-left: 5px;" data-plugin-slug="' + pluginSlug + '" href="' + my_plugin_vars.export_url + '">Export Tables</a>';
                    targetButton.after(buttonHTML);
                }
            });
        });
	</script>
	<?php
}

//scratch
function dp_admin_notice() {
	//warning notice example
	/*$class = 'notice notice-warning';
	$message = __( 'Before uninstalling the DocPort plugin, it is highly recommended to export your data.', 'docport' );
	$export_url = admin_url('admin-post.php?action=dp_export_action');
	$button_text = __( 'Export DocPort Data', 'docport' );

	printf( '<div class="%1$s"><p>%2$s <a href="%3$s" class="button button-primary">%4$s</a></p></div>',
		esc_attr( $class ),
		esc_html( $message ),
		esc_url( $export_url ),
		esc_html( $button_text )
	);*/
}
?>