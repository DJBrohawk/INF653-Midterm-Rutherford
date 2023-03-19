<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    //Set ID to update
    
    if(!isset($data->author)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    $auth->id = $data->id;
    $auth->author = $data->author;


    /*if(!isset($data->id)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }*/
    
    if(!$auth->checkId($auth->id)){
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

  

    //create post
    if($auth->update()) {
        echo json_encode(
            array(  'id' => $auth->id,
                    'author' => $auth->author
            )
        );
    } else {

        echo json_encode(array('message' => 'Author Not Updated'));

    }