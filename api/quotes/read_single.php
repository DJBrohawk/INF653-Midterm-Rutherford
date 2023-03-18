<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate author object

    $quo = new Quote($db);
    //get ID
   

    if(isset($_GET['id'])) {

        $quo->id = $_GET['id'];

    }

    if(isset($_GET['authorId'])) {

        $quo->authId = $_GET['authorId'];

    }

    if(isset($_GET['categoryId'])) {

        $quo->catId = $_GET['categoryId'];

    }

    //get author
    $result = $quo->read_single();

    //create array

    $num = $result->rowcount();

    // Check if any quotes

    if($num > 1){
        //Author array
        $quo_arr = array();
      //  $quo_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $quo_item = array(

                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
             

            );

            //push to "data"
            array_push($quo_arr, $quo_item);
        }

        //turn to JSON
        echo json_encode($quo_arr);

    }elseif ($num === 1){

        $row = $result->fetch(PDO::FETCH_ASSOC);
        extract($row);
        //set properties
    
        echo json_encode(array(

                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']

        ));


    } else {

        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }

    // $quo_arr = array(

    //     'id' => $quo->id,
    //     'author' => $quo->author,
    //     'category' => $quo->category,
    //     'quote' => $quo->quote
       
    // );


        //make JSON, print_r prints array
        //print_r(json_encode($quo_arr));



    ?>