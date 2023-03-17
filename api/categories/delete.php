<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object

    $cat = new Category($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if ( !isset($data->id) || !$cat->checkId($data->id)) {
        echo json_encode(array('message' => 'No Category Found'));
        exit();
    }

    //Set ID to update
    $cat->id = $data->id;

    //create post
    if($cat->delete()) {
        echo json_encode(
            array('message' => 'Category Deleted')
        );
    } else {

        echo json_encode(array('message' => 'Category Not Deleted'));

    }