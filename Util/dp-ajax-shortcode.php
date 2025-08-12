<?php

// todo figure out how to allow public access, admin-ajax blocks non-admin accounts

/* Shortcode Tables Retrieval */
function wp_ajax_dp_shortcode_document()
{
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

            //dynamically generate left join statements
            $camJoin = ($camId == 0) ? '' : " LEFT JOIN " . DP_TABLE_DOCUMENT_CAMPAIGNS . " ON " . DP_TABLE_DOCUMENTS . ".id = " . DP_TABLE_DOCUMENT_CAMPAIGNS . ".doc_id";
            $catJoin = ($catId == 0) ? '' : " LEFT JOIN " . DP_TABLE_DOCUMENT_CATEGORIES . " ON " . DP_TABLE_DOCUMENTS . ".id = " . DP_TABLE_DOCUMENT_CATEGORIES . ".doc_id";
            $platJoin = ($platId == 0) ? '' : " LEFT JOIN " . DP_TABLE_DOCUMENT_PLATFORMS . " ON " . DP_TABLE_DOCUMENTS . ".id = " . DP_TABLE_DOCUMENT_PLATFORMS . ".doc_id";

            //dynamically generate where statements
            $camWhere = ($camId == 0) ? '' : " WHERE " . DP_TABLE_DOCUMENT_CAMPAIGNS . ".cam_id = ". $camId;
            $catWhere = ($catId == 0) ? '' : (($camId == 0) ? " WHERE " : " AND ") . DP_TABLE_DOCUMENT_CATEGORIES . ".cam_id = ". $catId;
            $platWhere = ($platId == 0) ? '' : (($camId == 0 && $catId == 0) ? " WHERE " : " AND ") . DP_TABLE_DOCUMENT_PLATFORMS . ".plat_id = ". $platId;

            //combine into sql frankenstein
            $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENTS . $camJoin . $catJoin . $platJoin . $camWhere . $catWhere . $platWhere);

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

function wp_ajax_dp_shortcode_filters()
{
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!isset($_GET['data']['camId']) || !isset($_GET['data']['catId']) || !isset($_GET['data']['platId'])) {
            $response->code = 400;
            $response->status = 'error';
            $response->message = 'Missing Parameters';
        } else {
            try {
                $relationships['campaigns'] = $wpdb->get_results("SELECT DISTINCT cam_id, title FROM " . DP_TABLE_DOCUMENT_CAMPAIGNS . " LEFT JOIN " . DP_TABLE_CAMPAIGNS . " ON " . DP_TABLE_CAMPAIGNS . ".id = " .DP_TABLE_DOCUMENT_CAMPAIGNS . ".cam_id" . ((intval($_GET['data']['camId']) != 0) ? " WHERE cam_id = " . intval($_GET['data']['camId']) : ""));
                $relationships['categories'] = $wpdb->get_results("SELECT DISTINCT cat_id, title FROM " . DP_TABLE_DOCUMENT_CATEGORIES . " LEFT JOIN " . DP_TABLE_CATEGORIES . " ON " . DP_TABLE_CATEGORIES . ".id = " .DP_TABLE_DOCUMENT_CATEGORIES . ".cat_id" . ((intval($_GET['data']['catId']) != 0) ? " WHERE cat_id = " . intval($_GET['data']['catId']) : ""));
                $relationships['platforms'] = $wpdb->get_results("SELECT DISTINCT plat_id, title FROM " . DP_TABLE_DOCUMENT_PLATFORMS . " LEFT JOIN " . DP_TABLE_PLATFORMS . " ON " . DP_TABLE_PLATFORMS . ".id = " .DP_TABLE_DOCUMENT_PLATFORMS . ".plat_id" . ((intval($_GET['data']['platId']) != 0) ? " WHERE plat_id = " . intval($_GET['data']['platId']) : ""));

                $response->code = 200;
                $response->status = 'success';
                $response->message = "Filters Grabbed";
                $response->data = $relationships;
            } catch (\Exception $e) {
                $response->code = 400;
                $response->status = 'error';
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Invalid Request Type';
    }

    wp_send_json($response);
}
