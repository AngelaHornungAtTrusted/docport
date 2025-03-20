<?php
$plugin_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/';
require_once $plugin_path . 'wp-load.php';

/*
 * $wpdb & abspath are only not null when being referenced from Docport.php or root, not sure which
 * Read about paths & directory structure on Wordpress site to determine best practices
 */

global $wpdb;

$dbTableManager = new DbTableManager( $wpdb );

// Check if the form was submitted using the POST method
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	try {
		if (isset($_FILES['files'])) {

			$all_files = count($_FILES['files']['tmp_name']);

			for ($i = 0; $i < $all_files; $i++) {
				//document details
				$doc_name = $_FILES['files']['name'][$i];
				$doc_type = $_FILES['files']['type'][$i];
				$doc_size = $_FILES['files']['size'][$i];
				$doc_tmp = $_FILES['files']['tmp_name'][$i];
				$target_dir = dirname( dirname( __FILE__ ) ) . '/Uploads/';
				$target_file = $target_dir . $doc_name;

				/*if ($doc_size > 1000000) {
					throw new Exception("File size must be less than 2 MB");
				}*/

				move_uploaded_file($doc_tmp, $target_file);
				$dbTableManager->insertDocument($doc_name, $target_dir);
			}
			$response = array(
				'data' => array(
					'success' => 'success',
					'message' => 'Document Added!'
				)
			);
		}
	} catch ( \Exception $e ) {
		//todo exceptions not caught or returned, fix later
		$response = array(
			'data' => array(
				'success' => 'error',
				'message' => $e->getMessage(),
			)
		);
	}

	header( 'Content-Type: application/json' );
	echo json_encode($response);
	exit();
} elseif ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
	try {
		$response = array(
			'data' => array(
				'success' => 'success',
				'message' => 'Categories Acquired!',
				'content' => $dbTableManager->getCategory() // Renamed the inner 'data' key
			)
		);
	} catch ( \Exception $e ) {
		//todo exceptions not caught or returned, fix later
		$response = array(
			'data' => array(
				'status'  => 'error',
				'message' => $e->getMessage()
			)
		);
	}

	// To send the JSON response:
	header( 'Content-Type: application/json' );
	echo json_encode( $response );
	exit();
} else {
	// Handle cases where the PHP file is accessed directly without form submission
	echo "This page should be accessed through a form submission.";

}
?>
