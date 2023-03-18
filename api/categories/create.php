<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    if ( !isset($data->category)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    $cat->category = $data->category;
    $cat->id = $cat->getId() + 1;

    
    

    //create post
    if($cat->create()) {
        echo json_encode(
            array(  'id' => $cat->id,
                    'category' => $cat->category)
        );
    } else {

        echo json_encode(array('message' => 'Category Not Created'));

    }