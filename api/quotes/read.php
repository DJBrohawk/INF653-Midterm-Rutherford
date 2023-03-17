<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';


    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object

    $quo = new Quote($db);


    //blog post query
    $result = $quo->read();

    //get row count

    $num = $result->rowcount();

    // Check if any quotes

    if($num > 0){
        //Author array
        $quo_arr = array();
      //  $quo_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $quo_item = array(

                'id' => $id,
                'author' => $author,
                'category' => $category,
                'quote' => $quote

            );

            //push to "data"
            array_push($quo_arr, $quo_item);
        }

        //turn to JSON
        echo json_encode($quo_arr);

    } else {

        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }











?>