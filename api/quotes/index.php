<?php 

header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

        //may need a series of ifs here

    switch($method){

        case "GET":
            if(isset($_GET['id']) || isset($_GET['authorId']) || isset($_GET['categoryId'])){
            
                require "read_single.php";
            } else { 
              
                require "read.php";
            }
           // echo "This is " . $method;
            break;

        case "PUT":
            require "update.php";
           // echo "This is " . $method;
            break;

        case "POST":
            require "create.php";
           // echo "This is " . $method;   
            break;

        case "DELETE":
            require "delete.php";
           // echo "This is " . $method;
            break;

        default:
            echo "You've chosen an HTTP method that is not supported. Please choose between GET, POST, PUT, and DELETE.";
            exit();

    }

?>
