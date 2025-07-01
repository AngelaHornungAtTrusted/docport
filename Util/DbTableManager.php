<?php

namespace Util;
class DbTableManager
{

    public $dpdb;

    public function __construct($wpdb)
    {
        $this->dpdb = $wpdb;
    }

    public function initTables(): void
    {
        //global $wpdb;

        $charset_collate = $this->dpdb->get_charset_collate();

        //document table
        $itemTables = "CREATE TABLE " . DP_TABLE_DOCUMENTS . "(
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '' NOT NULL,
        path varchar(255) DEFAULT '' NOT NULL,
        thumbnail varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate; 
        CREATE TABLE " . DP_TABLE_CATEGORIES . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE " . DP_TABLE_PLATFORMS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE " . DP_TABLE_CAMPAIGNS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

        $relationalTables = "CREATE TABLE " . DP_TABLE_DOCUMENT_CATEGORIES . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        doc_id mediumint(9) NOT NULL DEFAULT '0',
        cat_id mediumint(9) NOT NULL DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE " . DP_TABLE_DOCUMENTS_PLATFORMS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        doc_id mediumint(9) NOT NULL DEFAULT '0',
        plat_id mediumint(9) NOT NULL DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE " . DP_TABLE_DOCUMENT_CAMPAIGNS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        doc_id mediumint(9) NOT NULL DEFAULT '0',
        cam_id mediumint(9) NOT NULL DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

        //data can be manipulated via js further for better reports
        $statisticalTables = "CREATE TABLE " . DP_TABLE_DOWNLOADS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        doc_id mediumint(9) NOT NULL DEFAULT '0',
        downloads int NOT NULL DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE " . DP_TABLE_USERS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        doc_id mediumint(9) NOT NULL DEFAULT '0',
        email varchar(255) DEFAULT '' NOT NULL,
        name varchar(255) DEFAULT '' NOT NULL,
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

        $settingsTables = "CREATE TABLE " . DP_TABLE_SETTINGS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        cam_id mediumint(9) NOT NULL DEFAULT '0',
        title varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0' NOT NULL,
        description varchar(255) DEFAULT '' NOT NULL,
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)) $charset_collate;";

        //require once is for dbDelta
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($itemTables);
        dbDelta($relationalTables);
        dbDelta($statisticalTables);
        dbDelta($settingsTables);
    }

    public function exportTables(): void
    {
        $tables_to_export = array(
            'dp_documents',
            'dp_document_categories',
        );

        $sql = '';
        foreach ($tables_to_export as $table) {
            $create_table = $this->dpdb->get_row("SHOW CREATE TABLE $table", ARRAY_A);
            $sql .= "-- Table structure for table `$table`\n";

            $sql .= $create_table["Create Table"] . ";\n\n";

            // Get the table data
            $rows = $this->dpdb->get_results("SELECT * FROM $table", ARRAY_A);
            if ($rows) {
                $column_names = array_keys($rows[0]);
                $sql .= "-- Dumping data for table `$table`\n";
                $sql .= "INSERT INTO `$table` (`" . implode("`,`", $column_names) . "`) VALUES\n";
                $values = array();
                foreach ($rows as $row) {
                    $value_str = "(";
                    $value_parts = array();
                    foreach ($row as $value) {
                        $value_parts[] = $this->dpdb->_real_escape($value);
                    }
                    $value_str .= "'" . implode("','", $value_parts) . "')";
                    $values[] = $value_str;
                }
                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }

        // Set headers for download
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="docport_tables_export.sql"');

        echo $sql;
        exit;
    }

    public function delTables(): void
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS dp_documents");
        $wpdb->query("DROP TABLE IF EXISTS dp_document_categories");
    }

    /* Category Management */
    function insertCategory($category): void
    {
        $this->dpdb->insert(
            'dp_document_categories',
            array(
                'title' => $category,
                'active' => 1,
                'create_date' => gmdate('Y-m-d H:i:s'),
                'update_date' => gmdate('Y-m-d H:i:s')
            )
        );
    }

    function getCategory($category = false)
    {
        if ($category == false) {
            //grab all
            return $this->dpdb->get_results("SELECT * FROM dp_document_categories");
        } else {
            //grab specific
            return $this->dpdb->get_row("SELECT * FROM dp_document_categories WHERE id = '$category'");
        }
    }

    //no deleting categories, just changing active status
    function updateCategoryStatus($categoryId, $checked)
    {
        $this->dpdb->update(
            'dp_document_categories',
            array(
                'active' => ($checked === "true" ? 1 : 0),
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            array('id' => $categoryId)
        );
    }

    function updateCategoryTitle($categoryId, $title)
    {
        $this->dpdb->update(
            'dp_document_categories',
            array(
                'title' => $title,
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            array('id' => $categoryId)
        );
    }

    /* Document Management */
    function insertDocument($dTitle, $dPath): void
    {
        $this->dpdb->insert(
            'dp_documents',
            array(
                'title' => $dTitle,
                'path' => $dPath,
                'cat_id' => 0,
                'active' => 1,
                'create_date' => gmdate('Y-m-d H:i:s'),
                'update_date' => gmdate('Y-m-d H:i:s')
            )
        );
    }

    function getDocument($dId = false, $cId = false)
    {
        if ($dId == false) {
            if ($cId == false) {
                //get all
                return $this->dpdb->get_results("SELECT * FROM dp_documents");
            }
            //get by category id
            return $this->dpdb->get_row("SELECT * FROM dp_documents WHERE cat_id = '$cId'");
        } else {
            //get by id
            return $this->dpdb->get_row("SELECT * FROM dp_document_categories WHERE id = '$dId'");
        }
    }

    function updateDocumentStatus($dId, $checked)
    {
        $this->dpdb->update(
            'dp_documents',
            array(
                'active' => ($checked === "true" ? 1 : 0),
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            array('id' => $dId)
        );
    }

    function updateDocumentTitle($dId, $title)
    {
        $this->dpdb->update(
            'dp_documents',
            array(
                'title' => $title,
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            array('id' => $dId)
        );
    }

    function updateDocumentCategory($dId, $cId)
    {
        $this->dpdb->update(
            'dp_documents',
            array(
                'cat_id' => intval($cId),
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            array('id' => $dId)
        );
    }

    function updateDocument()
    {

    }
}

?>