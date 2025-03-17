<?php
/*
 * Plugin Name: Docport
 * Description: Document Portfolio
 * Version: 1.0
 * Requires at least: 5.2
 * Requires PHP: 8.2.0
 * Author: Angela Hornung
 * Prefix: db
 */

/* variables & objects */
require_once(__DIR__ . '/Util/Db/DbTableManager.php');

/* Plugin Activation & Installation Management Hooks */
register_activation_hook(__FILE__, 'activation');
register_deactivation_hook(__FILE__, 'deactivation');
register_uninstall_hook(__FILE__, 'dp_uninstall');

add_action('admin_post_dp_export_action', 'dp_export_data');
add_action( 'admin_notices', 'dp_admin_notice' );
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_admin_scripts');

function activation(): void {
	dp_init();
}

function deactivation(): void {
	dp_un();
}

function dp_uninstall() {
	dp_un();
}

function dp_init() {
    //check db tables
	try{
        //make sure tables exist, dbDelta makes sure there are no duplicates
		$dpTableManager = new DbTableManager();
		$dpTableManager->initTables();
	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

function dp_un() {
	try{

	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

function dp_export_data() {
	try{
		$dpTableManager = new DbTableManager();

		// Output the SQL
		$dpTableManager->exportTables();
		exit;
	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

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

//scratch
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

	                console.log(pluginSlug);

                    var buttonHTML = '<a class="" style="margin-left: 5px;" data-plugin-slug="' + pluginSlug + '" href="' + my_plugin_vars.export_url + '">Export Tables</a>';
                    targetButton.after(buttonHTML);
                }
            });
        });
	</script>
	<?php
}
add_action('admin_footer', 'export_button');

function my_plugin_enqueue_admin_scripts($hook): void {
	//example of admin script
	if ('plugins.php' !== $hook) {
		return;
	}
	//wp_enqueue_style('my-plugin-admin-style', plugin_dir_url(__FILE__) . 'css/admin.css');
	wp_enqueue_script('my-plugin-admin-script', plugin_dir_url(__FILE__) . '', array('jquery'), '1.0', true);
	wp_localize_script('my-plugin-admin-script', 'my_plugin_vars', array(
		'nonce' => wp_create_nonce('my_plugin_custom_action'),
		'action' => 'my_plugin_custom_action',
		'export_url' => admin_url('admin-post.php?action=dp_export_action') // Add the URL here
	));
}
?>