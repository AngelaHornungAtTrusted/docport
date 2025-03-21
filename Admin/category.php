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
	//three post types, new category, change category status and update category title
	if ($_POST["dp-post-type"] == "1") {
		try{
			$dbTableManager->updateCategoryStatus($_POST["dp-cat-id"], $_POST["dp-cat-status"]);

			$response = array(
				'data' => array(
					'success'  => 'success',
					'message'  => 'Category Updated!',
				)
			);
		}catch(\Exception $e){
			//todo exceptions not caught or returned, fix later
			$response = array(
				'data' => array(
					'success'  => 'error',
					'message'  => $e->getMessage(),
				)
			);
		}
	} elseif ($_POST["dp-post-type"] == "2") {
		try{
			$dbTableManager->updateCategoryTitle($_POST["dp-cat-id"], $_POST["dp-cat-title"]);

			$response = array(
				'data' => array(
					'success'  => 'success',
					'message'  => 'Category Updated!',
				)
			);
		}catch(\Exception $e){
			//todo exceptions not caught or returned, fix later
			$response = array(
				'data' => array(
					'success'  => 'error',
					'message'  => $e->getMessage(),
				)
			);
		}
	} else {
		// Get the data from the form (awww, what you name the cat?)
		$catName = $_POST["dp-cat-name"]; // Replace with the actual name of your input field

		try {
			$dbTableManager->insertCategory($catName);

			$response = array(
				'data' => array(
					'success'  => 'success',
					'message'  => 'Category Added!',
					'content' => $catName
				)
			);
		} catch ( \Exception $e ) {
			//todo exceptions not caught or returned, fix later
			$response = array(
				'data' => array(
					'success'  => 'error',
					'message'  => $e->getMessage(),
				)
			);
		}
	}

	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
} elseif ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
	//todo implement multiple get method types
	try {
		$response = array(
			'data' => array(
				'success'  => 'success',
				'message'  => 'Categories Acquired!',
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
	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
} else {
	// Handle cases where the PHP file is accessed directly without form submission
	echo "This page should be accessed through a form submission.";

}

?>