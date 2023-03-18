<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    if ( !isset($data->authorId)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    if ( !isset($data->categoryId)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    if ( !isset($data->quote)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    //put data from raw posted data into quote object
    $quo->authId = $data->authorId;
    $quo->catId = $data->categoryId;
    $quo->quote = $data->quote;
    $quo->id = $quo->getId() + 1;

    if(!$quo->checkCatId($quo->catId)) {
        echo json_encode(array("message" => "category_id Not Found"));
        exit();
    }

    if(!$quo->checkAuthId($quo->authId)) {
        echo json_encode(array("message" => "author_id Not Found"));
        exit();
    }

    //echo $quo->id;

    //create post
    if($quo->create()) {
        echo json_encode(
            array(  'id' => $quo->id,
                    'quote' => $quo->quote,
                    'author_id' => $quo->authId,
                    'category_id' => $quo->catId)
        );
    } else {

        echo json_encode(array('message' => 'Quote Not Created'));

    }