<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object

    $quo = new Quote($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if ( !isset($data->author_id) || !isset($data->category_id) || !isset($data->id) || !isset($data->quote)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    //Set ID to update
    $quo->id = $data->id;
    $quo->authId = $data->author_id;
    $quo->catId = $data->category_id;
    $quo->quote = $data->quote;

    if(!$quo->checkCatId($quo->catId)) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit();
    }

    if(!$quo->checkAuthId($quo->authId)) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    if(!$quo->checkId($quo->id)){
        echo json_encode(array('message' => 'No Quotes Found'));
        exit();
    }

    //create post
    if($quo->update()) {

        
        echo json_encode(
            array(
                    'id' => $quo->id,
                    'author_id' => $quo->authId,
                    'category_id' => $quo->catId,
                    'quote' => $quo->quote), JSON_FORCE_OBJECT
        );
    } else {

        echo json_encode(array('message' => 'Quote Not Updated'));

    }