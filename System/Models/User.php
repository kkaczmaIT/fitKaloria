<?php
    namespace System\Models;
    use System\Database;
    
    class User
    {
        private $db;
        private $dbSql;
        public function __construct() 
        {
            $this->db = new Database;
            $this->dbSql = $this->db->getConnectionSql(); 
        }

        public function index()
        {
            $this->getUsers();
        }

        /**
         * Return user data if user login is in database
         *
         * @param [type] $login - login given in register form or in request
         * @return [bool] object/false  - object user or false
         */
        public function findUserByLogin($login)
        {
            $this->db->loadQuery('SELECT ID, loginU, _password, email, firstname, lastname, weight FROM USERS WHERE loginU=:loginU');
            $this->db->bind(':loginU', $login);

            if($this->db->executeQuery())
            {
                $user = $this->db->single();
                if($user)
                    return $user;
                else
                {
                    return false;
                }
                    
            }
        }

        /**
         * Return user data if email is in database
         *
         * @param [type] $email - user email
         * @return [object/bool] - user data or false
         */
        public function findUserByEmail($email)
        {
            $this->db->loadQuery('SELECT ID, loginU, _password, email, firstname, lastname, weight FROM USERS WHERE email=:email');
            $this->db->bind(':email', $email);

            if($this->db->executeQuery())
            {
                $user = $this->db->single();
                if($user)
                    return $user;
                else
                {
                    return false;
                }
                    
            }
        }

        /**
         * Create new user in database
         *
         * @param [string] $loginU - name user
         * @param [string] $email - email format required
         * @param [string] $firstname - personal data
         * @param [string] $lastname - personal data
         * @param [string] $password - secure data
         * @param [float] $weight - weight body
         * @return void
         */
        public function register($loginU, $email, $firstname, $lastname, $password, $weight)
        {
            $this->db->loadQuery('INSERT INTO USERS(loginU, email, firstname, lastname, _password, weight, created_at) VALUES(:login, :email, :firstname, :lastname, :password, :weight, NOW())');
            // Bind value
            $this->db->bind(':login', $loginU);
            $this->db->bind(':email', $email);
            $this->db->bind(':firstname', $firstname);
            $this->db->bind(':lastname', $lastname);
            $this->db->bind(':password', password_hash($password, PASSWORD_BCRYPT));
            $this->db->bind(':weight', $weight);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        /**
         * Method to login user in system. Create SESSION data.
         *
         * @param [string] $login - login user
         * @param [string] $password - password user
         * @return [object/bool] - userData/false
         */
        public function login($login, $password)
        {
            $userData = $this->findUserByLogin($login);
            if($userData)
            {
                if(password_verify($password, $userData['_password']))
                {
                    return $userData;
                }
            }
            else
            {
                return false;
            }
        }

        /**
         * Update User password
         *
         * @param [integer] $ID - ID user
         * @param [string] $_password - new password
         * @return [bool]  - true/false
         */
        public function updateUserPassword($ID, $_password)
        {
            $this->db->loadQuery('UPDATE USERS SET _password=:_password WHERE ID=:ID');
            $this->db->bind(':_password', $_password);
            $this->db->bind(':ID', $ID);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
            
        }

        /**
         * Update user weight
         *
         * @param [integer] $ID - ID user
         * @param [float] $_weight - weight user
         * @return [bool] - true/false
         */
        public function updateUserWeight($ID, $_weight)
        {
            $this->db->loadQuery('UPDATE USERS SET weight=:_weight WHERE ID=:ID');
            $this->db->bind(':_weight', $_weight);
            $this->db->bind(':ID', $ID);
            
            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        /**
         * Return list of Users
         *
         * @return [array/bool] array/false
         */
        public function getUsers()
        {
            $this->db->loadQuery('SELECT ID, loginU, email, firstname, lastname, weight FROM USERS');
            $users = $this->db->resultSet();
            if($this->db->rowCount() >= 0)
            {
                return $users;
            }
            else
            {
                return false;
            }
        }

        
        /**
         * Return list of Users
         * @param [integer] - ID
         * @return [array/bool] array/false
         */
        public function getUserByID($ID)
        {
            $this->db->loadQuery('SELECT ID, loginU, email, firstname, lastname, weight FROM USERS WHERE ID=:_ID');
            $this->db->bind(':_ID', $ID);
            $user = $this->db->single();

            if($user)
            {
                return $user;
            }
            else
            {
                return false;
            }
        }

    }

?>