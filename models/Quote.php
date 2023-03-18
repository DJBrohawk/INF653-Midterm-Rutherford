<?php


    class Quote {
        private $conn;
        private $table = 'quotes';

        public $id;
        public $category;
        public $authId;
        public $catId;
        public $author;
        public $quote;
        

        public function __construct($db){
            $this->conn = $db;
        }

        //get quotes

        public function read(){

            $query = 'SELECT 
                q.id,
                q.quote, 
                a.author,
                c.category
                FROM
                 ' . $this->table . 
                 ' q 
                 LEFT JOIN authors a 
                 ON a.id = q.author_id 
                 LEFT JOIN categories c 
                 ON c.id = q.category_id
                 ORDER BY
                 q.id ASC';

            $stmt = $this->conn->prepare($query);

            //execute query
            $stmt->execute();

            return $stmt;

        }

        //get single post
        public function read_single(){

           
                //initializing statement variable to be used in the if statements and outside of them
                $stmt = '';

                //I think the best way to do this is to check the four possible scenarios of what variables might be set
               
                //if only the ID is set
                if(isset($_GET['id']) && !isset($_GET['authorID']) && !isset($_GET['categoryID'])){

                    
                    $query = 'SELECT 
                q.id,
                q.quote, 
                a.author,
                c.category
                FROM
                 ' . $this->table . 
                 ' q 
                 LEFT JOIN authors a 
                 ON a.id = q.author_id 
                 LEFT JOIN categories c 
                 ON c.id = q.category_id
                 WHERE
                 q.id = :id 
                 ORDER BY
                 q.id ASC';

                 //prepare statement
                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':id', $this->id);

              

                    //if only the author id is set
                } elseif (!isset($_GET['id']) && isset($_GET['authorId']) && !isset($_GET['categoryId'])) {

                   
           
                    $query = 'SELECT 
                q.id,
                q.quote, 
                a.author,
                c.category
                FROM
                 ' . $this->table . 
                 ' q 
                 LEFT JOIN authors a 
                 ON a.id = q.author_id 
                 LEFT JOIN categories c 
                 ON c.id = q.category_id
                 WHERE
                 a.id = :authId
                 ORDER BY
                 q.id ASC';

                 //prepare statement
                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':authId', $this->authId);
                   

        

                    //if only the category id is set
                } elseif (!isset($_GET['id']) && !isset($_GET['authorId']) && isset($_GET['categoryId'])) {

                  

                    $query = 'SELECT 
                q.id,
                q.quote, 
                a.author,
                c.category
                FROM
                 ' . $this->table . 
                 ' q 
                 LEFT JOIN authors a 
                 ON a.id = q.author_id 
                 LEFT JOIN categories c 
                 ON c.id = q.category_id
                 WHERE
                 c.id = :catId
                 ORDER BY
                 q.id ASC';

                 $stmt = $this->conn->prepare($query);

                 $stmt->bindParam(':catId', $this->catId);
                 

                    //if both the category and author ids are set
                } elseif  (!isset($_GET['id']) && isset($_GET['authorId']) && isset($_GET['categoryId'])) {

                    $query = 'SELECT 
                q.id,
                q.quote, 
                a.author,
                c.category
                FROM
                 ' . $this->table . 
                 ' q 
                 LEFT JOIN authors a 
                 ON a.id = q.author_id 
                 LEFT JOIN categories c 
                 ON c.id = q.category_id
                 WHERE
                 c.id = :catId
                 AND
                 a.id = :authId
                 ORDER BY
                 q.id ASC';

                 $stmt = $this->conn->prepare($query);

                 $stmt->bindParam(':authId', $this->authId);
                 $stmt->bindParam(':catId', $this->catId);
                


                }
           

             //prepare statement
            //  $stmt = $this->conn->prepare($query);

            //  $stmt->bindParam(':id', $this->id);

             $stmt->execute();

             return $stmt;
             //the below is antiquated from the practice project to the midterm specs
             /*

             $row = $stmt->fetch(PDO::FETCH_ASSOC);

             //set properties
             //maybe need to set id here?
             $this->category = $row['category'];
             $this->author = $row['author'];
             $this->quote = $row['quote'];
             $this->id = $row['id'];
             */
             
        }

        //get most recent ID used for this table
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

        public function create(){
            //create query
            $query = 'INSERT INTO ' . $this->table . ' (id, quote, author_id, category_id) VALUES (:id, :quote, :authorId, :categoryId)';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
           
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->authId = htmlspecialchars(strip_tags($this->authId));
            $this->catId = htmlspecialchars(strip_tags($this->catId));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            //Bind data
          
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':authorId', $this->authId);
            $stmt->bindParam(':categoryId', $this->catId);
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
                category_id = :categoryId,
                author_id = :authorId,
                quote = :quote
                WHERE id = :id';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
            
            $this->catId = htmlspecialchars(strip_tags($this->catId));
            $this->authId = htmlspecialchars(strip_tags($this->authId));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':categoryId', $this->catId);
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':authorId', $this->authId);
            $stmt->bindParam(':id', $this->id);

            //execute query
            if($stmt->execute()) {
                
                if($stmt->rowcount() === 0){
                    echo json_encode(array('message' => "No Quotes Found"));
                    exit();
                }
                
                return true;
            
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

        public function checkCatId($catId){
           
            //create query
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

        public function checkAuthId($authId){

             //create query
             $query = 'SELECT id from authors';

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

        public function checkId($quoId){

            $query = 'SELECT id from quotes';

             $stmt = $this->conn->prepare($query);
 
 
             if($stmt->execute()) {
 
 
                 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 
                     extract($row);
         
                     if($id === $quoId){
                    
                         return true;
                     }
 
                 }
 
             
             }
 
             return false;
        }

    }

?>