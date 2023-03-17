<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';


    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object

    $cat = new Category($db);


    //blog post query
    $result = $cat->read();

    //get row count

    $num = $result->rowcount();

    // Check if any authors

    if($num > 0){
        //Author array
        $cat_arr = array();
      //  $cat_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $cat_item = array(

                'id' => $id,
                'category' => $category

            );

            //push to "data"
            array_push($cat_arr, $cat_item);
        }

        //turn to JSON
        echo json_encode($cat_arr);

    } else {

        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }











?>