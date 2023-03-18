<?php


    class Author {
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db){
            $this->conn = $db;
        }

        //get authors

        public function read(){

            $query = 'SELECT 
                id,
                author
                FROM
                 ' . $this->table . '
                 ORDER BY
                 id ASC';

            $stmt = $this->conn->prepare($query);

            //execute query
            $stmt->execute();

            return $stmt;

        }

        //get single post
        public function read_single(){
            $query = 'SELECT 
            id,
            author 
            FROM
             ' . $this->table . ' 
             WHERE id = ?';

             //prepare statement
             $stmt = $this->conn->prepare($query);

             $stmt->bindParam(1, $this->id);

             $stmt->execute();

             /*
             $row = $stmt->fetch(PDO::FETCH_ASSOC);

             //set properties
             //maybe need to set id here?
             $this->author = $row['author'];
             if($this->author === null){
                echo json_encode(array('message' => 'author_id Not Found'));
                exit();
             }
             */

             return $stmt;
             
        }

        public function create(){
            //create query
            $query = 'INSERT INTO ' . $this->table . ' (id, author) VALUES (:id, :author)';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
           
            $this->author = htmlspecialchars(strip_tags($this->author));
            
            //Bind data
          
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);
            //execute query
            if($stmt->execute()) {return true;
            
            }

            printf("Error: %s. \n", $stmt->error);

            return false;

        }


        public function update(){


            //create query
            $query = 'UPDATE ' . $this->table . '
            SET 
                author = :author
                WHERE id = :id';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
            
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            //execute query
            if($stmt->execute()) {return true;
            
            }

            printf("Error: %s. \n", $stmt->error);

            return false;


        }

        public function delete(){
            //create query
            $query = 'DELETE FROM '. $this->table . ' WHERE id = :id';


            //prepare statement
            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':id', $this->id);


             //execute query
             if($stmt->execute()) {return true;
            
             }
 
             printf("Error: %s. \n", $stmt->error);
 
             return false;

        }

        public function getId(){
            $query = '
            SELECT
            id
            FROM
            ' . $this->table . '
            ORDER BY
            id
            DESC
            LIMIT 1';

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()) {

                $result = $stmt->fetch();

                return $result[0];
            
            }

            printf("Error: %s. \n", $stmt->error);

            return false;

        }

        public function checkId($authId){

            $query = 'SELECT id from quotes';

             $stmt = $this->conn->prepare($query);
 
 
             if($stmt->execute()) {
 
 
                 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 
                     extract($row);
         
                     if($id === $authId){
                    
                         return true;
                     }
 
                 }
 
             
             }
 
             return false;
        }

    }

?>