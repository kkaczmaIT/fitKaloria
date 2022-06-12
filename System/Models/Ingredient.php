<?php
    namespace System\Models;
    use System\Database;
    
    class Dish
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
        public function getDishes()
        {
            $this->db->loadQuery('SELECT ID, name, amount_calories, amount_portion, recommend_portion FROM DISHES');
            $dishes = $this->db->resultSet();
            if($this->db->rowCount() >= 0)
            {
                return $dishes;
            }
            else
            {
                return false;
            }
        }

        public function getDish($ID)
        {
            $this->db->loadQuery('SELECT ID, name, amount_calories, amount_portion, recommend_portion FROM DISHES WHERE ID=:_ID');
            $this->db->bind(':_ID', $ID);
            $dish = $this->db->single();
            if($this->db->rowCount() > 0)
            {
                return $dish;
            }
            else
            {
                return false;
            }
        }
        
        /**
         * Return list of dishes which user add in specific day
         * @param [integer] - $year - year
         * @param [integer] - $month - month
         * @param [integer] - $year - year
         * @return [array/bool] array/false
         */
        public function getUserDishesFromDay($year, $month, $day, $ID_meal)
        {
            $this->db->loadQuery('SELECT D.ID, D.name AS dish_name, D.amount_calories, D.amount_portion, D.recommend_portion, M.name AS meal_name FROM DISHES D INNER JOIN DISHES_DAY DD ON D.ID=DD.ID_dishes INNER JOIN MEAL M ON DD.ID_meal=M.ID WHERE year(DD.date)=:_year AND month(DD.date)=:_month AND day(DD.date)=:_day AND DD.ID_user=:_ID_user AND DD.ID_meal=:_ID_meal');
            $this->db->bind(':_year', $year);
            $this->db->bind(':_month', $month);
            $this->db->bind(':_day', $day);
            $this->db->bind(':_ID_meal', $ID_meal);
            $this->db->bind(':_ID_user', $_SESSION['user_id']);
            $userDishes = $this->db->resultSet();

            if($userDishes)
            {
                return $userDishes;
            }
            else
            {
                return false;
            }
        }

        public function getFitnessPlanDishesMealType($ID_fitness_plan, $ID_meal)
        {
            $this->db->loadQuery('SELECT D.ID, D.name, D.amount_calories, D.amount_portion, D.recommend_portion FROM DISHES D INNER JOIN FITNESS_PLAN_DISHES_INGREDIENTS FPDI ON D.ID=FPDI.ID_dishes INNER JOIN  FITNESS_PLAN FP ON FPDI.ID_fitness_plan=FP.ID WHERE FPDI.ID_fitness_plan=:_ID_fitness_plan AND FPDI.ID_meal=:_ID_meal');
            $this->db->bind(':_ID_fitness_plan', $ID_fitness_plan);
            $this->db->bind(':_ID_meal', $ID_meal);
            $planDishes = $this->db->resultSet();

            if($this->db->rowCount() >= 0)
            {
                return $planDishes;
            }
            else
            {
                return false;
            }
        }

      

        public function create($name, $amount_calories, $amount_portion, $recommend_portion)
        {
            $this->db->loadQuery('INSERT INTO DISHES (name, amount_calories, amount_portion, recommend_portion) VALUES(:name, :amount_calories, :amount_portion, :recommend_portion)');
            $this->db->bind(':name', $name);
            $this->db->bind(':amount_calories', $amount_calories);
            $this->db->bind(':amount_portion', $amount_portion);
            $this->db->bind(':recommend_portion', $recommend_portion);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function addDishesDay($ID_dishes, $ID_meal)
        {
            $this->db->loadQuery('INSERT INTO DISHES_DAY (ID_meal, ID_user, ID_dishes) VALUES(:ID_meal, :ID_user, :ID_dishes)');
            $this->db->bind(':ID_meal', $ID_meal);
            $this->db->bind(':ID_dishes', $ID_dishes);
            $this->db->bind(':ID_user', $_SESSION['user_id']);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        

        public function addFitnessPlanDishes($ID_dishes, $ID_meal, $ID_fitness_plan)
        {
            $this->db->loadQuery('INSERT INTO FITNESS_PLAN_DISHES_INGREDIENTS (ID_fitness_plan, ID_dishes, ID_meal) VALUES(:ID_fitness_plan, :ID_dishes, :ID_meal)');
            $this->db->bind(':ID_meal', $ID_meal);
            $this->db->bind(':ID_dishes', $ID_dishes);
            $this->db->bind(':ID_fitness_plan', $ID_fitness_plan);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function edit($name, $amount_calories, $amount_portion, $recommend_portion, $ID)
        {
            $this->db->loadQuery('UPDATE DISHES SET name=:name, amount_calories=:amount_calories, amount_portion=:amount_portion, recommend_portion=:recommend_portion WHERE ID=:ID');
            $this->db->bind(':name', $name);
            $this->db->bind(':amount_calories', $amount_calories);            
            $this->db->bind(':amount_portion', $amount_portion);
            $this->db->bind(':recommend_portion', $recommend_portion);
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
            $this->db->loadQuery('DELETE FROM DISHES WHERE ID=:ID');
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

        public function deleteDishDay($ID)
        {
            $this->db->loadQuery('DELETE FROM DISHES_DAY WHERE ID=:_ID');
            $this->db->bind(':_ID', $ID);
            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function deleteFitnessPlanDish($ID)
        {
            $this->db->loadQuery('DELETE FROM FITNESS_PLAN_DISHES_INGREDIENTS WHERE ID=:_ID');
            $this->db->bind(':_ID', $ID);
            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

?>