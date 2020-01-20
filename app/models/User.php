<?php
    class User{
        private $db;

        public function __construct()
        {
            $this->db = new Database;
        }

        //Find user by email
        public function findUserByEmail($email){
            $this->db->query("SELECT * FROM users WHERE email = :email");
            $this->db->bind(':email', $email, PDO::PARAM_STR);

            $row = $this->db->single();

            //Check row
            if($this->db->rowCount() > 0){
                return true;
            }

            return false;
        }
    }