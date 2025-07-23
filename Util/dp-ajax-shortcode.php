<?php

/* Shortcode Tables Retrieval */
function wp_ajax_dp_shortcode_documents() {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['docId'])) {
            //grab specific doc
            $docId = intval($_GET['data']['docId']);
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_DOCUMENTS . " WHERE id = " . $docId);

            $response->code = 200;
            $response->status = 'success';
            $response->message = "Document Grabbed";
            $response->data = $data;
        } else {
            //determine what IDs we have
            $camId = ($_GET['data']['camId'] == 0) ? null : intval($_GET['data']['camId']);
            $catId = ($_GET['data']['catId'] == 0) ? null : intval($_GET['data']['catId']);
            $platId = ($_GET['data']['platId'] == 0) ? null : intval($_GET['data']['platId']);

            //todo use left joins to grab all documents and their associations
            $data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "term_taxonomy" . " LEFT JOIN " . $wpdb->prefix . "terms" . " ON " . $wpdb->prefix . "terms" . ".term_id = " . $wpdb->prefix . "term_taxonomy" . ".term_id");

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

function wp_ajax_dp_shortcode_campaigns () {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['camId'])) {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CAMPAIGNS . " WHERE id = " . intval($_GET['data']['camId']));
        } else {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CAMPAIGNS);
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

function wp_ajax_dp_shortcode_categories () {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['catId'])) {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CATEGORIES . " WHERE id = " . intval($_GET['data']['catId']));
        } else {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_CATEGORIES);
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

function wp_ajax_dp_shortcode_platforms () {
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']['platformId'])) {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_DOCUMENT_PLATFORMS . " WHERE id = " . intval($_GET['data']['platformId']));
        } else {
            $data = $wpdb->get_results('SELECT * FROM ' . DP_TABLE_PLATFORMS);
        }

        $response->code = 200;
        $response->status = 'success';
        $response->message = "Platforms Grabbed";
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

/*
            //grab docs by some association type
            $table = (isset($_GET['data']['camId'])) ? DP_TABLE_DOCUMENT_CAMPAIGNS : ((isset($_GET['data']['catId'])) ? DP_TABLE_DOCUMENT_CATEGORIES : DP_TABLE_DOCUMENT_PLATFORMS);
            $matchId = (isset($_GET['data']['camId'])) ? 'cam_id' : ((isset($_GET['data']['catId'])) ? 'cat_id' : 'plat_id');
            $id = (isset($_GET['data']['camId'])) ? intval($_GET['data']['camId']) : ((isset($_GET['data']['catId'])) ? intval($_GET['data']['catId']) : intval($_GET['data']['platId']));

            //grab array of documents ids
            $docIds = $wpdb->get_results('SELECT * FROM ' . $table . " WHERE " . $matchId . " = " . $id);

            $data = [];

            foreach ($docIds as $docId) {
                //todo investigate cleaner way to do this
                $data[$docId] = $wpdb->get_results('SELECT * FROM ' . $table . " WHERE id = " . intval($docId));
            }
 */
