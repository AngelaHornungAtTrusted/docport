<?php

use JetBrains\PhpStorm\NoReturn;

class DbTableManager
{

	#[NoReturn] public function initTables(): void {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

		//document table
        $dpTable = "CREATE TABLE dp_documents (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(55) DEFAULT '' NOT NULL,
        path varchar(55) DEFAULT '' NOT NULL,
        cat_id mediumint(9) NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

	    $catTable = "CREATE TABLE dp_document_categories (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(55) DEFAULT '' NOT NULL,
        path varchar(55) DEFAULT '' NOT NULL,
        cat_id mediumint(9) NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

        //require once is for dbDelta
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($dpTable);
		dbDelta($catTable);
    }

	#[NoReturn] public function exportTables() : void
	{
		global $wpdb;
		$tables_to_export = array(
			'dp_documents',
			'dp_document_categories',
		);

		$sql = '';
		foreach ($tables_to_export as $table) {
			$create_table = $wpdb->get_row("SHOW CREATE TABLE $table", ARRAY_A);
			$sql .= "-- Table structure for table `$table`\n";

			$sql .= $create_table["Create Table"] . ";\n\n";

			// Get the table data
			$rows = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
			if ($rows) {
				$column_names = array_keys($rows[0]);
				$sql .= "-- Dumping data for table `$table`\n";
				$sql .= "INSERT INTO `$table` (`" . implode("`,`", $column_names) . "`) VALUES\n";
				$values = array();
				foreach ($rows as $row) {
					$value_str = "(";
					$value_parts = array();
					foreach ($row as $value) {
						$value_parts[] = $wpdb->_real_escape_string($value);
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

	public function delTables(){
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS dp_documents");
		$wpdb->query("DROP TABLE IF EXISTS dp_document_categories");
	}
}

?>