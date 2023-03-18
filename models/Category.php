<?php


    class Category {
        private $conn;
        private $table = 'categories';

        public $id;
        public $category;

        public function __construct($db){
            $this->conn = $db;
        }

        //get authors

        public function read(){

            $query = 'SELECT 
                id,
                category
                FROM
                 ' . $this->table;

            $stmt = $this->conn->prepare($query);

            //execute query
            $stmt->execute();

            return $stmt;

        }

        //get single post
        public function read_single(){
            $query = 'SELECT 
            id,
            category 
            FROM
             ' . $this->table . ' 
             WHERE id = :id';

             //prepare statement
             $stmt = $this->conn->prepare($query);

             $stmt->bindParam(':id', $this->id);

             $stmt->execute();
            return $stmt;

             /*
             $row = $stmt->fetch(PDO::FETCH_ASSOC);

             //set properties
             //maybe need to set id here?
             $this->category = $row['category'];
             */
        }

        public function create(){
            //create query
            $query = 'INSERT INTO ' . $this->table . ' (id, category) VALUES (:id, :category)';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
           
            $this->category = htmlspecialchars(strip_tags($this->category));
            
            //Bind data
          
            $stmt->bindParam(':category', $this->category);
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
                category = :category
                WHERE id = :id';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
            
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':category', $this->category);
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
             if($stmt->execute()) {
                
                return true;
            
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

        public function checkId($catId){

            $query = 'SELECT id from categories';

             $stmt = $this->conn->prepare($query);
 
 
             if($stmt->execute()) {
 
 
                 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 
                     extract($row);
         
                     if($id === $catId){
                    
                         return true;
                     }
 
                 }
 
             
             }
 
             return false;
        }

    }

?>