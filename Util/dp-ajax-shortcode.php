<?php

/* Shortcode Tables Retrieval */
function wp_ajax_dp_shortcode_document() {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['docId'])) {
            //grab specific doc
            $docId = intval($_GET['data']['docId']);
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_DOCUMENTS . " WHERE id = " . $docId . ' AND active = 1');

            $response->code = 200;
            $response->status = 'success';
            $response->message = "Document Grabbed";
            $response->data = $data;
        } else {
            //determine what IDs we have
            $camId = ($_GET['data']['camId'] == 0) ? 0 : intval($_GET['data']['camId']);
            $catId = ($_GET['data']['catId'] == 0) ? 0 : intval($_GET['data']['catId']);
            $platId = ($_GET['data']['platId'] == 0) ? 0 : intval($_GET['data']['platId']);

            $camSql = ($camId == 0) ? '' : " LEFT JOIN " . DP_TABLE_DOCUMENT_CAMPAIGNS . " ON " . DP_TABLE_DOCUMENTS . ".id = " . DP_TABLE_DOCUMENT_CAMPAIGNS . ".doc_id WHERE " . DP_TABLE_DOCUMENT_CAMPAIGNS . ".id = " . $camId;
            $catSql = ($catId == 0) ? '' : " LEFT JOIN " . DP_TABLE_DOCUMENT_CATEGORIES . " ON " . DP_TABLE_DOCUMENTS . ".id = " . DP_TABLE_DOCUMENT_CATEGORIES . ".doc_id WHERE " . DP_TABLE_DOCUMENT_CATEGORIES . ".id = " . $catId;
            $platSql = ($platId == 0) ? '' : " LEFT JOIN " . DP_TABLE_DOCUMENT_PLATFORMS . " ON " . DP_TABLE_DOCUMENTS . ".id = " . DP_TABLE_DOCUMENT_PLATFORMS . ".doc_id WHERE " . DP_TABLE_DOCUMENT_PLATFORMS . ".id = " . $camId;

            //todo fix sql, got where statements before left joins which isn't allowed, so we need to seperate them
            echo "SELECT * FROM " . DP_TABLE_DOCUMENTS . $camSql . $catSql . $platSql;
            die();

            //todo use left joins to grab all documents and their associations
            $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENTS . $camSql . $catSql . $platSql);

            $response->code = 200;
            $response->status = 'success';
            $response->message = "Documents Grabbed";
            $response->data = $data;
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_shortcode_campaign () {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['camId'])) {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CAMPAIGNS . " WHERE id = " . intval($_GET['data']['camId']));
        } else {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CAMPAIGNS . " WHERE active = 1");
        }

        $response->code = 200;
        $response->status = 'success';
        $response->message = "Campaigns Grabbed";
        $response->data = $data;
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_shortcode_category () {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['catId'])) {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CATEGORIES . " WHERE id = " . intval($_GET['data']['catId']));
        } else {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CATEGORIES . " WHERE active = 1");
        }

        $response->code = 200;
        $response->status = 'success';
        $response->message = "Categories Grabbed";
        $response->data = $data;
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_shortcode_platform () {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['platformId'])) {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_DOCUMENT_PLATFORMS . " WHERE id = " . intval($_GET['data']['platformId']));
        } else {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_PLATFORMS . " WHERE active = 1");
        }

        $response->code = 200;
        $response->status = 'success';
        $response->message = "Platforms Grabbed";
        $response->data = $data;
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}
