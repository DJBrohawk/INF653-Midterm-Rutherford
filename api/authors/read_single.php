<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate author object

    $auth = new Author($db);
    //get ID
    $auth->id = isset($_GET['id']) ? $_GET['id'] : die();
/*
    //get author
    $auth->read_single();

    //create array

    $auth_arr = array(

        'id' => $auth->id,
        'author' => $auth->author
       
    );

        //make JSON, print_r prints array
        print_r(json_encode($auth_arr));
        */


        //get author
    $result = $auth->read_single();

    //create array

    $num = $result->rowcount();

    // Check if any quotes

    if($num > 0){
        //Author array
     
      //  $quo_arr['data'] = array();

      $row = $result->fetch(PDO::FETCH_ASSOC)

            extract($row);

        //turn to JSON
        echo json_encode(array(
           
            'id' => $id,
            'author' => $author    

        ));

    } else {

        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }


    ?>