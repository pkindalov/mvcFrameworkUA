<?php
    /*
     * PDO Database class
     * Connect to database
     * Create prepared statements
     * Bind values
     * Return rows and values
    */
    class Database{
        private $host;
        private $user;
        private $pass;
        private $dbname;

        private $dbh;
        private $stmt;
        private $error;

        public function __construct()
        {

            $this->host = getenv('DB_HOST');
            $this->user = getenv('DB_USER');
            $this->pass = getenv('DB_PASS');
            $this->dbname = getenv('DB_NAME');

            //Set Dsn
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            //Create PDO instance
            try{
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            } catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }

        }

        //Prepare statement with query
        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        } 

        //Bind values
        public function bind($param, $value, $type){
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                    break;
                    case is_bool($value):
                       $type = PDO::PARAM_BOOL;
                   break;    
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                    break;  
                    default:
                        $type = PDO::PARAM_STR;
                    break;    
                }
            }

            $this->stmt->bindValue($param, $value, $type);
        }

        //Execute prepared statement
        public function execute(){
            return $this->stmt->execute();
        }

        //Get result set as an array of objects
        public function resultSet(){
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        //Get single record as object
        public function single(){
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);    
        }

        //Get row count
        public function rowCount(){
            return $this->stmt->rowCount();
        }

        //get last inserted id
        public function lastInsertedId(){
            return $this->dbh->lastInsertId();
        }

    }
