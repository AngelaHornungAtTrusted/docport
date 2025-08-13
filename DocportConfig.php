<?php
/* Global General Config */
define('DP_PLUGIN_NAME', 'Document Portfolio');
define('DP_PLUGIN_SLAG', 'document-portfolio');
define('DP_PLUGIN_VERSION', '1.0.0');

/* Global File Paths */
define('DP_ROOT_DIR_NAME', 'docport');
define('DP_ROOT_DIR_PATH', plugin_dir_path(__FILE__));
define('DP_ADMIN_DIR_PATH',  DP_ROOT_DIR_PATH . 'Admin');
define('DP_ASSETS_DIR_PATH',  DP_ROOT_DIR_PATH . 'Assets');
define('DP_SHORTCODE_DIR_PATH',  DP_ROOT_DIR_PATH . 'Shortcode');
define('DP_UTIL_DIR_PATH',  DP_ROOT_DIR_PATH . 'Util');

/* Global File Urls */
define('DP_ROOT_DIR_URL', plugin_dir_url(__FILE__));
define('DP_ADMIN_URL', DP_ROOT_DIR_URL . 'Admin');
define('DP_ASSETS_URL', DP_ROOT_DIR_URL . 'Assets');
define('DP_SHORTCODE_URL', DP_ROOT_DIR_URL . 'Shortcode');
define('DP_UTIL_URL', DP_ROOT_DIR_URL . 'Util');

/* Global Database Details */
global $wpdb;
define('DP_PLUGIN_PREFIX', 'dp');
define('DP_DB_PREFIX', 'dp_');

//tables
define('DP_TABLE_CAMPAIGNS', DP_DB_PREFIX . 'campaigns');
define('DP_TABLE_CATEGORIES', DP_DB_PREFIX . 'categories');
define('DP_TABLE_DOCUMENTS', DP_DB_PREFIX . 'documents');
define('DP_TABLE_PLATFORMS', DP_DB_PREFIX . 'platforms');

//relational tables
define('DP_TABLE_DOCUMENT_CAMPAIGNS', DP_DB_PREFIX . 'document_campaigns');
define('DP_TABLE_DOCUMENT_CATEGORIES', DP_DB_PREFIX . 'document_categories');
define('DP_TABLE_DOCUMENT_PLATFORMS', DP_DB_PREFIX . 'document_platforms');

//settings tables
define('DP_TABLE_SETTINGS', DP_DB_PREFIX . 'settings');

//statistical tables
define('DP_TABLE_DOWNLOADS', DP_DB_PREFIX . 'downloads');
define('DP_TABLE_DOWNLOAD_DATES', DP_DB_PREFIX . 'download_dates');
define('DP_TABLE_USERS', DP_DB_PREFIX . 'users');

//data constants
define('DP_STATUS_ACTIVE', 1);
define('DP_STATUS_INACTIVE', 0);
define('DP_DEFAULT_MESSAGE', 'Default Message');
define('DP_AUTOINACTIVE_SETTINGS_NAME', 'autoinactive');
define('DP_AUTODELETE_SETTINGS_NAME', 'autodelete');
define('DP_AUTOINACTIVE_SETTINGS_DESCRIPTION', 'Marks document status as inactive after redemption.');
define('DP_AUTODELETE_SETTINGS_DESCRIPTION', 'Deletes document when called if their expiration date has passed.');
define('DP_SETTINGS_DATA', [['name' => DP_AUTOINACTIVE_SETTINGS_NAME, 'description' => DP_AUTODELETE_SETTINGS_DESCRIPTION], ['name' => DP_AUTODELETE_SETTINGS_NAME, 'description' => DP_AUTODELETE_SETTINGS_DESCRIPTION]]);