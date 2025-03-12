<?php
namespace docport\util\db;
class dpTableManager
{

    protected const PREFIX = 'dp_';

    public function initTables()
    {
        global $wpdb;

        $table_name = self::PREFIX . 'custom_table';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(55) DEFAULT '' NOT NULL,
        path varchar(55) DEFAULT '' NOT NULL,
        cat_id mediumint(9) NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL
        PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}

?>