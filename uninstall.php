<?php

/* variables & objects */
require_once(__DIR__ . '/Util/Db/DbTableManager.php');

if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

/*
 * Delete tables
 */

try{
	//make sure tables exist, dbDelta makes sure there are no duplicates
	$dpTableManager = new DbTableManager();
	$dpTableManager->delTables();
} catch (\Exception $e) {
	//todo implement cleaner and more proper error reporting
	var_dump($e->getMessage());
}