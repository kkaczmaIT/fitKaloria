<?php
    namespace System\Models;
    use System\Database;
    
    class Fitnessplan
    {
        private $db;
        private $dbSql;
        public function __construct() 
        {
            $this->db = new Database;
            $this->dbSql = $this->db->getConnectionSql(); 
        }


        /**
         * Return list of plans
         *
         * @return [array/bool] array/false
         */
        public function getFitnessPlans()
        {
            $this->db->loadQuery('SELECT FP.ID, FP.name, FP.type, FP.tags, FP.description, FP.daily_protein, FP.daily_carbohydrates, FP.daily_fat, FP.num_dishes, FP.energy_aim, U.firstname, U.lastname FROM FITNESS_PLAN FP INNER JOIN USERS U ON FP.ID_author=U.ID');
            $fitnessPlans = $this->db->resultSet();
            if($this->db->rowCount() >= 0)
            {
                return $fitnessPlans;
            }
            else
            {
                return false;
            }
        }

        
        /**
         * Return list of plans
         * @param [integer] - ID
         * @return [array/bool] array/false
         */
        public function getFitnessPlanByID($ID)
        {
            $this->db->loadQuery('SELECT FP.ID, FP.name, FP.type, FP.tags, FP.description, FP.daily_protein, FP.daily_carbohydrates, FP.daily_fat, FP.num_dishes, FP.energy_aim FROM FITNESS_PLAN WHERE FP.ID=:ID_fitness');
            $this->db->bind(':ID_fitness', $ID);
            $fitnessPlan = $this->db->single();

            if($fitnessPlan)
            {
                return $fitnessPlan;
            }
            else
            {
                return false;
            }
        }

        public function getFitnessPlansUserConsument()
        {
            $this->db->loadQuery('SELECT  FP.ID, FP.name, FP.type, FP.tags, FP.description, FP.daily_protein, FP.daily_carbohydrates, FP.daily_fat, FP.num_dishes, FP.energy_aim, U2.firstname, U2.lastname FROM USERS U INNER JOIN USERS_FITNESS_PLAN UFP ON U.ID = UFP.ID_user INNER JOIN FITNESS_PLAN FP ON UFP.ID_fitness_plan=FP.ID INNER JOIN USERS U2 ON FP.ID_author=U2.ID WHERE U.ID=:_ID_user');
            $this->db->bind(':_ID_user', $_SESSION['user_id']);
            $userPlan = $this->db->resultSet();

            if($this->db->rowCount() >= 0)
            {
                return $userPlan;
            }
            else
            {
                return false;
            }
        }

        public function getFitnessPlansAuthor($ID)
        {
            $this->db->loadQuery('SELECT U.ID, U.email, U.firstname, U.lastname, FP.name, FP.type, FP.tags, FP.description, FP.daily_protein, FP.daily_carbohydrates, FP.daily_fat, FP.num_dishes, FP.energy_aim FROM USERS U INNER JOIN FITNESS_PLAN FP ON U.ID=FP.ID_author WHERE FP.ID_author=:ID_user');
            $this->db->bind(':ID_user', $ID);
            $authorPlans = $this->db->resultSet();

            if($this->db->rowCount() >= 0)
            {
                return $authorPlans;
            }
            else
            {
                return false;
            }
        }

        public function create($name, $ID_author, $type, $tags, $description, $daily_protein, $daily_carbohydrates, $daily_fat, $num_dishes)
        {
            $this->db->loadQuery('INSERT INTO FITNESS_PLAN (name, ID_author, type, tags, description, daily_protein, daily_carbohydrates, daily_fat, num_dishes, energy_aim) VALUES(:name, :ID_author, :type, :tags, :description, :daily_protein, :daily_carbohydrates, :daily_fat, :num_dishes, :energy_aim)');
            $this->db->bind(':name', $name);
            $this->db->bind(':type', $type);
            $this->db->bind(':tags', $tags);
            $this->db->bind(':description', $description);
            $this->db->bind(':daily_protein', $daily_protein);
            $this->db->bind(':daily_carbohydrates', $daily_carbohydrates);
            $this->db->bind(':daily_fat', $daily_fat);
            $this->db->bind(':num_dishes', $num_dishes);
            $this->db->bind(':ID_author', $ID_author);
            $this->db->bind(':energy_aim', $daily_protein * 4 + $daily_carbohydrates * 4 + $daily_fat * 7);
            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        
        public function edit($ID, $name, $ID_author, $type, $tags, $description, $daily_protein, $daily_carbohydrates, $daily_fat, $num_dishes)
        {
            $this->db->loadQuery('UPDATE FITNESS_PLAN SET name=:name, ID_author=:ID_author, type=:type, tags=:tags, description=:description, daily_protein=:daily_protein, daily_carbohydrates=:daily_carbohydrates, daily_fat=:daily_fat, num_dishes=:num_dishes, energy_aim=:energy_aim WHERE ID=:ID');
            $this->db->bind(':name', $name);
            $this->db->bind(':ID_author', $ID_author);            
            $this->db->bind(':type', $type);
            $this->db->bind(':tags', $tags);
            $this->db->bind(':description', $description);
            $this->db->bind(':daily_protein', $daily_protein);
            $this->db->bind(':daily_carbohydrates', $daily_carbohydrates);
            $this->db->bind(':daily_fat', $daily_fat);
            $this->db->bind(':num_dishes', $num_dishes);
            $this->db->bind(':energy_aim', $daily_protein * 4 + $daily_carbohydrates * 4 + $daily_fat * 7);
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

        public function delete($ID)
        {
            $this->db->loadQuery('DELETE FROM FITNESS_PLAN WHERE ID=:ID');
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

        //INSERT INTO ID FP check if exists, check if record not exists create

    }

?>