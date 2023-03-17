<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate author object

    $cat = new Category($db);
    //get ID
    $cat->id = isset($_GET['id']) ? $_GET['id'] : die();

    

    //get author
    $cat->read_single();

    //create array
    if($cat->id !== null){
    $cat_arr = array(

        'id' => $cat->id,
        'category' => $cat->category
       
    );

        if($cat->category === null) {
            echo (json_encode(array("message" => "category_id not found")));
            exit();
        }
        //make JSON, print_r prints array
        print_r(json_encode($cat_arr));

    } 

    ?>