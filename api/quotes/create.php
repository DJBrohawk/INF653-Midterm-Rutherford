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

    if ( !isset($data->author_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    if ( !isset($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    if ( !isset($data->quote)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    //put data from raw posted data into quote object
    $quo->authId = $data->author_id;
    $quo->catId = $data->category_id;
    $quo->quote = $data->quote;
    $quo->id = $quo->getId() + 1;

    //use check ID functions to determine if ID passed in is in the database at all
    if(!$quo->checkCatId($quo->catId)) {
        echo json_encode(array('message' => 'category_id Not Found'), JSON_FORCE_OBJECT);
        exit();
    }

    if(!$quo->checkAuthId($quo->authId)) {
        echo json_encode(array('message' => 'author_id Not Found'), JSON_FORCE_OBJECT);
        exit();
    }

    //echo $quo->id;

    //create quote if function executes successfully
    if($quo->create()) {
        echo json_encode(
            array(  'id' => $quo->id,
                    'quote' => $quo->quote,
                    'author_id' => $quo->authId,
                    'category_id' => $quo->catId), JSON_FORCE_OBJECT);
    } else {

        echo json_encode(array('message' => 'Quote Not Created'));

    }