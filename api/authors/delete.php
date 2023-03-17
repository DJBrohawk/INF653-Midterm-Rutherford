<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object

    $auth = new Author($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if ( !isset($data->id) || !$auth->checkId($data->id)) {
        echo json_encode(array('message' => 'No Author Found'));
        exit();
    }

    //Set ID to update
    $auth->id = $data->id;

    //create post
    if($auth->delete()) {
        echo json_encode(
            array('message' => 'Author Deleted')
        );
    } else {

        echo json_encode(array('message' => 'Author Not Deleted'));

    }