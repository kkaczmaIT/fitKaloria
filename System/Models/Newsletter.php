<?php
    namespace System\Models;
    use System\Database;
    
    class Newsletter
    {
        private $db;
        private $dbSql;
        public function __construct() 
        {
            $this->db = new Database;
            $this->dbSql = $this->db->getConnectionSql(); 
        }

        /**
         * Add new record to NEWSLETTER table
         *
         * @param [type] $ID_user - ID user to newsletter
         * @return [bool] true/false
         */
        public function create($ID_user)
        {
            $this->db->loadQuery('INSERT INTO NEWSLETTER (ID_user) VALUES(:ID_user)');
            $this->db->bind(':ID_user', $ID_user);
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
         * Delete user from NEWSLETTER table
         *
         * @param [type] $ID_user - ID user to delete from table
         * @return [bool] true/false
         */
        public function delete($ID_user)
        {
            $this->db->loadQuery('DELETE FROM NEWSLETTER WHERE ID_user=:ID_user');
            $this->db->bind(':ID_user', $ID_user);
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
         * Check if model exists
         *
         * @param [type] $ID_user - ID of user to ceck if is in newsletter
         * @return [bool] - false/true
         */
        public function checkIfExists($ID_user)
        {
            $this->db->loadQuery('SELECT ID, ID_user FROM NEWSLETTER WHERE ID_user=:ID_user');
            $this->db->bind(':ID_user', $ID_user);
            if($this->db->single())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        /**
         * get list of newsletters
         *
         * @return [array/false] - array email or false
         */
        public function fetchNewsletterEmails()
        {
            $this->db->loadQuery('SELECT U.email FROM USERS U INNER JOIN NEWSLETTER N ON U.ID = N.ID_user');

            $newsletterlist = $this->db->resultSet();
            if($this->db->rowCount() > 0)
            {
                return $newsletterlist;
            }
            else
            {
                return false;
            }
        }

        /**
         * Get single email signed in newsletter
         *
         * @param [type] $ID_user - id user signed to newsletter
         * @return [bool] array/false
         */
        public function fetchNewsletterEmail($ID_user)
        {
            $this->db->loadQuery('SELECT U.email FROM USERS U INNER JOIN NEWSLETTER N ON U.ID = N.ID_user WHERE U.ID=:ID_user');
            $this->db->bind(':ID_user', $ID_user);

            $newsletter = $this->db->single();
            if($newsletter)
            {
                return $newsletter;
            }
            else
            {
                return false;
            }
        }
    }
?>