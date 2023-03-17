<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';


    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object

    $auth = new Author($db);


    //blog post query
    $result = $auth->read();

    //get row count

    $num = $result->rowcount();

    // Check if any authors

    if($num > 0){
        //Author array
        $auth_arr = array();
       // $auth_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $auth_item = array(

                'id' => $id,
                'author' => $author

            );

            //push to "data"
            array_push($auth_arr, $auth_item);
        }

        //turn to JSON
        echo json_encode($auth_arr);

    } else {

        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }











?>