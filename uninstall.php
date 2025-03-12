<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/*
 * Warn user about table removal and documents staying in place
 * Export tables .sql for download
 * Delete tables
 */

global $wpdb;
var_dump($wpdb);
die();