<?php

/* Entity Tables Management */
function wp_ajax_dp_campaign()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    //determine call type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //determine if new or existing
        if (!isset($_POST['data']['campaignId'])) {
            //new campaign
            //todo implement adding custom settings for each campaign into the campaign settings table
            try {

                if (!isset($_POST['data']['title']) || !isset($_POST['data']['active'])) {
                    $response->code = 400;
                    $response->status = 'error';
                    $response->message = 'Missing required fields';

                    wp_send_json($response);
                }

                $title = sanitize_text_field($_POST['data']['title']);
                $active = ($_POST['data']['active'] == "true") ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                $wpdb->insert(
                    DP_TABLE_CAMPAIGNS,
                    array(
                        'title' => $title,
                        'active' => $active,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s'),
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Campaign Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //existing campaign
            try {
                $id = intval($_POST['data']['campaignId']);

                if (!isset($_POST['data']['title'])) {
                    //id only means delete
                    $wpdb->delete(
                        DP_TABLE_CAMPAIGNS,
                        array('id' => $id),
                    );

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Campaign Deleted';
                } else {

                    //not id only means update
                    $title = sanitize_text_field($_POST['data']['title']);
                    $active = ($_POST['data']['active'] == "true") ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                    $wpdb->update(
                        DP_TABLE_CAMPAIGNS,
                        array(
                            'title' => $title,
                            'active' => $active,
                        ),
                        array('id' => $id)
                    );

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Campaign Updated';
                }
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //determine if specific or all
        if (!isset($_GET['data']['campaignId'])) {
            //check for document id
            if(!isset($_GET['data']['documentId'])){
                //return all
                try {
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_CAMPAIGNS);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Campaigns received';
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            } else {
                //return association with document
                try {
                    $id = intval($_GET['data']['campaignId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_CAMPAIGNS . " WHERE id = " . $id);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Campaign received';
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            }
        } else {
            //return specific
            try {
                $id = intval($_GET['data']['campaignId']);
                $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_CAMPAIGNS . " WHERE id = " . $id);

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Campaign received';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_category()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    //determine call type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //determine if new or existing
        if (!isset($_POST['data']['categoryId'])) {
            //new campaign
            try {
                if (!isset($_POST['data']['title']) || !isset($_POST['data']['active'])) {
                    $response->code = 400;
                    $response->status = 'error';
                    $response->message = 'Missing required fields';

                    wp_send_json($response);
                }

                $title = sanitize_text_field($_POST['data']['title']);
                $active = ($_POST['data']['active'] == "true") ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                $wpdb->insert(
                    DP_TABLE_CATEGORIES,
                    array(
                        'title' => $title,
                        'active' => $active,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s'),
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Category Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //existing campaign
            try {
                $id = intval($_POST['data']['categoryId']);

                if (!isset($_POST['data']['title'])) {
                    //id only means delete
                    $wpdb->delete(
                        DP_TABLE_CATEGORIES,
                        array('id' => $id),
                    );

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Category Deleted';
                } else {
                    //not id only means update
                    $title = sanitize_text_field($_POST['data']['title']);
                    $active = ($_POST['data']['active'] == "true") ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                    $wpdb->update(
                        DP_TABLE_CATEGORIES,
                        array(
                            'title' => $title,
                            'active' => $active,
                        ),
                        array('id' => $id)
                    );

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Category Updated';
                }
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //determine if specific or all
        if (!isset($_GET['data']['categoryId'])) {
            //return all
            try {
                $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_CATEGORIES);

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Categories received';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //return specific
            try {
                $id = intval($_GET['data']['categoryId']);
                $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_CATEGORIES . " WHERE id = " . $id);

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Campaigns received';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_document()
{
    global $wpdb;
    $response = new stdClass();
    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['data']['id'])) {
            //no id means new document
            if (!isset($_POST['data']['title']) || !isset($_POST['data']['path'])) {
                $response->code = 400;
                $response->status = 'error';
                $response->message = 'Missing required fields';

                wp_send_json($response);
            }

            try {
                $wpdb->insert(
                    DP_TABLE_DOCUMENTS,
                    array(
                        'title' => $_POST['data']['title'],
                        'path' => $_POST['data']['path'],
                        'thumbnail' => $_POST['data']['thumbnail'],
                        'active' => DP_STATUS_ACTIVE,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s'),
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //id means existing document
            $id = intval($_POST['data']['id']);

            if (!isset($_POST['data']['title'])) {
                //id only means delete

                try {
                    //delete document reference and all associations
                    $wpdb->delete(DP_TABLE_DOCUMENTS, array('id' => $id));
                    $wpdb->delete(DP_TABLE_DOCUMENT_CAMPAIGNS, array('doc_id' => $id));
                    $wpdb->delete(DP_TABLE_DOCUMENT_CATEGORIES, array('doc_id' => $id));
                    $wpdb->delete(DP_TABLE_DOCUMENT_PLATFORMS, array('doc_id' => $id));

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Document Deleted';
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            } else {
                try {
                    $title = sanitize_text_field($_POST['data']['title']);
                    $url = sanitize_text_field($_POST['data']['path']);
                    $thumbnail = sanitize_text_field($_POST['data']['thumbnail']);
                    $active = (intval($_POST['data']['active']) == 1) ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                    try {
                        $wpdb->update(
                            DP_TABLE_DOCUMENTS,
                            array(
                                'title' => $title,
                                'path' => $url,
                                'thumbnail' => $thumbnail,
                                'active' => $active,
                                'update_date' => gmdate('Y-m-d H:i:s'),
                            ),
                            array('id' => $id)
                        );
                    } catch (Exception $e) {
                        $response->code = 500;
                        $response->status = 'error';
                        $response->message = $e->getMessage();
                    }
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //todo implement get methods
        if (!isset($_GET['data']['id'])) {
            //no id means get all documents
            try {
                $data = ['documents' => $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENTS),
                    'cams' => $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_CAMPAIGNS),
                    'cats' => $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_CATEGORIES),
                    'plats' => $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_PLATFORMS)];

                $response->code = 200;
                $response->status = 'success';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            $id = intval($_POST['data']['id']);

            try {
                $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENTS . " WHERE id = " . $id);

                $response->code = 200;
                $response->status = 'success';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    }
    wp_send_json($response);
}

function wp_ajax_dp_platform()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    //determine call type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //determine if new or existing
        if (!isset($_POST['data']['platformId'])) {
            //new campaign
            try {
                if (!isset($_POST['data']['title']) || !isset($_POST['data']['active'])) {
                    $response->code = 400;
                    $response->status = 'error';
                    $response->message = 'Missing required fields';

                    wp_send_json($response);
                }

                $title = sanitize_text_field($_POST['data']['title']);
                $active = ($_POST['data']['active'] == "true") ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                $wpdb->insert(
                    DP_TABLE_PLATFORMS,
                    array(
                        'title' => $title,
                        'active' => $active,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s'),
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Platform Type Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //existing campaign
            try {
                $id = intval($_POST['data']['platformId']);

                if (!isset($_POST['data']['title'])) {
                    //id only means delete
                    $wpdb->delete(
                        DP_TABLE_PLATFORMS,
                        array('id' => $id),
                    );

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Platform Deleted';
                } else {
                    //not id only means update
                    $title = sanitize_text_field($_POST['data']['title']);
                    $active = ($_POST['data']['active'] == "true") ? DP_STATUS_ACTIVE : DP_STATUS_INACTIVE;

                    $wpdb->update(
                        DP_TABLE_PLATFORMS,
                        array(
                            'title' => $title,
                            'active' => $active,
                        ),
                        array('id' => $id)
                    );

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = 'Platform Updated';
                }
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //determine if specific or all
        if (!isset($_GET['data']['platformId'])) {
            //return all
            try {
                $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_PLATFORMS);

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Platforms received';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //return specific
            try {
                $id = intval($_GET['data']['campaignId']);
                $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_PLATFORMS . " WHERE id = " . $id);

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Platform received';
                $response->data = $data;
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

/* Relational Tables Management */
function wp_ajax_dp_doc_cam()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    if (!isset($_POST['data']['docId']) || !isset($_POST['data']['camId']) || !isset($_POST['data']['checked'])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Parameters';
    }

    //determine call type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //determine if new or existing

        if ($_POST['data']['checked'] == "true") {
            //true means checked so create new association
            try {
                $docId = intval($_POST['data']['docId']);
                $camId = intval($_POST['data']['camId']);

                $wpdb->insert(
                    DP_TABLE_DOCUMENT_CAMPAIGNS,
                    array(
                        'doc_id' => $docId,
                        'cam_id' => $camId,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s')
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Association Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //if unchecked we delete
            try {
                $docId = intval($_POST['data']['docId']);
                $camId = intval($_POST['data']['camId']);

                $wpdb->delete(
                    DP_TABLE_DOCUMENT_CAMPAIGNS,
                    array('doc_id' => $docId, 'cam_id' => $camId),
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Association Deleted';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //todo implement table joins for further details on campaign or documents (later feature)
        //determine if all or specific
        if (!isset($_GET['data']['id'])) {
            //if docId then all associations for a document, otherwise all associations for a campaign
            if (!isset($_GET['data']['docId'])) {
                try {
                    $docId = intval($_GET['data']['docId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_CAMPAIGNS . " WHERE doc_id = " . $docId);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = "Grabbed Associations";
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            } else {
                try {
                    $camId = intval($_GET['data']['camId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_CAMPAIGNS . " WHERE cam_id = " . $camId);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = "Grabbed Associations";
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_doc_cat()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    if (!isset($_POST['data']['docId']) || !isset($_POST['data']['catId']) || !isset($_POST['data']['checked'])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Parameters';
    }

    //determine call type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //determine if new or existing

        if ($_POST['data']['checked'] == "true") {
            //true means checked so create new association
            try {
                $docId = intval($_POST['data']['docId']);
                $catId = intval($_POST['data']['catId']);

                $wpdb->insert(
                    DP_TABLE_DOCUMENT_CATEGORIES,
                    array(
                        'doc_id' => $docId,
                        'cat_id' => $catId,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s')
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Association Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //if unchecked we delete
            try {
                $docId = intval($_POST['data']['docId']);
                $catId = intval($_POST['data']['catId']);

                $wpdb->delete(
                    DP_TABLE_DOCUMENT_CATEGORIES,
                    array('doc_id' => $docId, 'cat_id' => $catId),
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Association Deleted';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //todo implement table joins for further details on campaign or documents (later feature)
        //determine if all or specific
        if (!isset($_GET['data']['id'])) {
            //if docId then all associations for a document, otherwise all associations for a campaign
            if (!isset($_GET['data']['docId'])) {
                try {
                    $docId = intval($_GET['data']['docId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_CATEGORIES . " WHERE doc_id = " . $docId);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = "Grabbed Associations";
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            } else {
                try {
                    $catId = intval($_GET['data']['catId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_CATEGORIES . " WHERE cat_id = " . $catId);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = "Grabbed Associations";
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

function wp_ajax_dp_doc_plat()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    if (!isset($_POST['data']['docId']) || !isset($_POST['data']['platId']) || !isset($_POST['data']['checked'])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Parameters';
    }

    //determine call type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //determine if new or existing

        if ($_POST['data']['checked'] == "true") {
            //true means checked so create new association
            try {
                $docId = intval($_POST['data']['docId']);
                $platId = intval($_POST['data']['platId']);

                $wpdb->insert(
                    DP_TABLE_DOCUMENT_PLATFORMS,
                    array(
                        'doc_id' => $docId,
                        'plat_id' => $platId,
                        'create_date' => gmdate('Y-m-d H:i:s'),
                        'update_date' => gmdate('Y-m-d H:i:s')
                    )
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Association Created';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        } else {
            //if unchecked we delete
            try {
                $docId = intval($_POST['data']['docId']);
                $platId = intval($_POST['data']['platId']);

                $wpdb->delete(
                    DP_TABLE_DOCUMENT_PLATFORMS,
                    array('doc_id' => $docId, 'plat_id' => $platId),
                );

                $response->code = 200;
                $response->status = 'success';
                $response->message = 'Document Association Deleted';
            } catch (Exception $e) {
                $response->code = 500;
                $response->status = 'error';
                $response->message = $e->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //todo implement table joins for further details on campaign or documents (later feature)
        //determine if all or specific
        if (!isset($_GET['data']['id'])) {
            //if docId then all associations for a document, otherwise all associations for a campaign
            if (!isset($_GET['data']['docId'])) {
                try {
                    $docId = intval($_GET['data']['docId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_PLATFORMS . " WHERE doc_id = " . $docId);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = "Grabbed Associations";
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            } else {
                try {
                    $platId = intval($_GET['data']['platId']);
                    $data = $wpdb->get_results("SELECT * FROM " . DP_TABLE_DOCUMENT_PLATFORMS . " WHERE plat_id = " . $platId);

                    $response->code = 200;
                    $response->status = 'success';
                    $response->message = "Grabbed Associations";
                    $response->data = $data;
                } catch (Exception $e) {
                    $response->code = 500;
                    $response->status = 'error';
                    $response->message = $e->getMessage();
                }
            }
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Bad Request';
    }

    wp_send_json($response);
}

/* Settings Tables */
//todo implement request handlers for settings tables

/* Statistical Tables */
//todo implement request handlers for statistical tables
function wp_ajax_pp_downloads() {

}

/* Shortcode Tables Retrieval */
function wp_ajax_dp_shortcode() {
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