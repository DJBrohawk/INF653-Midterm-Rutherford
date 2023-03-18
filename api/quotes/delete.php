<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
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

    if ( !isset($data->id) || !$quo->checkId($data->id)) {
        echo json_encode(array('message' => 'No Quotes Found'));
        exit();
    }

    //Set ID to update
    $quo->id = $data->id;

    //create post
    if($quo->delete()) {
        echo json_encode(
            array(
                    'id' => $quo->id
                    )
        );
    } else {

        echo json_encode(array('message' => 'Quote Not Deleted'));

    }