<?php

class DbTableManager
{

	public $dpdb;

	public function __construct($wpdb) {
		$this->dpdb = $wpdb;
	}

	public function initTables(): void {
        //global $wpdb;

        $charset_collate = $this->dpdb->get_charset_collate();

		//document table
        $dpTable = "CREATE TABLE dp_documents (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '' NOT NULL,
        path varchar(255) DEFAULT '' NOT NULL,
        cat_id mediumint(9) NOT NULL,
        active tinyint(1) DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

	    $catTable = "CREATE TABLE dp_document_categories (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '' NOT NULL,
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

	public function exportTables() : void
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

	public function delTables(): void {
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS dp_documents");
		$wpdb->query("DROP TABLE IF EXISTS dp_document_categories");
	}

	/* Category Management */
	function insertCategory($category): void {
		$this->dpdb->insert(
			'dp_document_categories',
			array(
				'title' => $category,
				'active' => 1,
				'create_date' => gmdate('Y-m-d H:i:s'),
				'update_date'  => gmdate('Y-m-d H:i:s')
			)
		);
	}

	function getCategory($category = false) {
		if ($category == false) {
			//grab all
			return $this->dpdb->get_results("SELECT * FROM dp_document_categories");
		} else {
			//grab specific
			return $this->dpdb->get_row("SELECT * FROM dp_document_categories WHERE id = '$category'");
		}
	}

	//no deleting categories, just changing active status
	function updateCategoryStatus($categoryId, $checked) {
		$this->dpdb->update(
			'dp_document_categories',
			array(
				'active' => ($checked === "true" ? 1:0),
				'update_date' => gmdate('Y-m-d H:i:s')
			),
			array('id' => $categoryId)
		);
	}

	function updateCategoryTitle($categoryId, $title) {
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
	function insertDocument($dTitle, $dPath): void {
		$this->dpdb->insert(
			'dp_documents',
			array(
				'title' => $dTitle,
				'path' => $dPath,
				'cat_id' => 0,
				'active' => 1,
				'create_date' => gmdate('Y-m-d H:i:s'),
				'update_date'  => gmdate('Y-m-d H:i:s')
			)
		);
	}

	function updateDocument(){
		
	}

	function deleteDocument(){

	}
}

?>