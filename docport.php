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

/* Plugin Activation & Installation Hooks */

use docport\util\db\dpTableManager;

function activation() {
    dp_init();
}

function deactivation() {

}

register_activation_hook(__FILE__, 'activation');
register_deactivation_hook(__FILE__, 'deactivation');
register_uninstall_hook(__FILE__, 'dp_uninstall');

function dp_init() {
    //check db tables
	try{
		$dpTableManager = new dpTableManager();
		$dpTableManager->initTables();
	} catch (\Exception $e) {
		//todo impliment cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}
?>