<?php
    class User{
        private $db;

        public function __construct()
        {
            $this->db = new Database;
        }

        //Registering User
        public function register($data){
            $this->db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

            //Bind values
            $this->db->bind(":name", $data['name'], null);
            $this->db->bind(":email", $data['email'], null); 
            $this->db->bind(":password", $data['password'], null);

            //Execute query
            if($this->db->execute()){
                return true;
            }

            return false;
        }

        //Find user by email
        public function findUserByEmail($email){
            $this->db->query("SELECT * FROM users WHERE email = :email");
            $this->db->bind(':email', $email, null);

            $row = $this->db->single();

            //Check row
            if($this->db->rowCount() > 0){
                return true;
            }

            return false;
        }
    }