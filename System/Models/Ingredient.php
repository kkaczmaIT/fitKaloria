<?php
    namespace System\Models;
    use System\Database;
    
    class Ingredient
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
        public function getIngredients()
        {
            $this->db->loadQuery('SELECT ID, name, calories, protein, carbohydrates, fat, fat_full, fat_unfill FROM INGREDIENTS');
            $ingredients = $this->db->resultSet();
            if($this->db->rowCount() >= 0)
            {
                return $ingredients;
            }
            else
            {
                return false;
            }
        }

        public function getIngredient($ID)
        {
            $this->db->loadQuery('SELECT ID, name, calories, protein, carbohydrates, fat, fat_full, fat_unfill FROM INGREDIENTS WHERE ID=:_ID');
            $this->db->bind(':_ID', $ID);
            $ingredient = $this->db->single();
            if($this->db->rowCount() > 0)
            {
                return $ingredient;
            }
            else
            {
                return false;
            }
        }
        
        /**
         * Return list of ingredients which dish consist of
         * @param [integer] - $year - year
         * @param [integer] - $month - month
         * @param [integer] - $year - year
         * @return [array/bool] array/false
         */
        public function getIngredientsDish($ID_dishes)
        {
            $this->db->loadQuery('SELECT I.ID, I.name, I.calories, I.protein, I.carbohydrates, I.fat, I.fat_full, I.fat_unfill FROM INGREDIENTS I INNER JOIN DISHES_INGREDIENTS DI ON I.ID=DI.ID_ingredients WHERE DI.ID_dishes=:_ID_dishes');
            $this->db->bind(':_ID_dishes', $ID_dishes);
            $dishIngredients = $this->db->resultSet();

            if($dishIngredients)
            {
                return $dishIngredients;
            }
            else
            {
                return false;
            }
        }

        public function create($name, $calories, $protein, $carbohydrates, $fat, $fat_full, $fat_unfill)
        {
            $this->db->loadQuery('INSERT INTO INGREDIENTS (name, calories, protein, carbohydrates, fat, fat_full, fat_unfill) VALUES(:name, :calories, :protein, :carbohydrates, :fat, :fat_full, :fat_unfill)');
            $this->db->bind(':name', $name);
            $this->db->bind(':calories', $calories);
            $this->db->bind(':protein', $protein);
            $this->db->bind(':carbohydrates', $carbohydrates);
            $this->db->bind(':fat', $fat);
            $this->db->bind(':fat_full', $fat_full);
            $this->db->bind(':fat_unfill', $fat_unfill);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function addDishesIngredient($ID_dishes, $ID_ingredients)
        {
            $this->db->loadQuery('INSERT INTO DISHES_INGREDIENTS (ID_dishes, ID_ingredients) VALUES(:ID_dishes, :ID_ingredients)');
            $this->db->bind(':ID_dishes', $ID_dishes);
            $this->db->bind(':ID_ingredients', $ID_ingredients);

            if($this->db->executeQuery())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        

       
        public function edit($name, $calories, $protein, $carbohydrates, $fat, $fat_full, $fat_unfill, $ID)
        {
            $this->db->loadQuery('UPDATE INGREDIENTS SET name=:name, calories=:calories, protein=:protein, carbohydrates=:carbohydrates, fat=:fat, fat_full=:fat_full, fat_unfill=:fat_unfill WHERE ID=:ID');
            $this->db->bind(':name', $name);
            $this->db->bind(':calories', $calories);            
            $this->db->bind(':protein', $protein);
            $this->db->bind(':carbohydrates', $carbohydrates);
            $this->db->bind(':fat', $fat);
            $this->db->bind(':fat_full', $fat_full);
            $this->db->bind(':fat_unfill', $fat_unfill);
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
            $this->db->loadQuery('DELETE FROM INGREDIENTS WHERE ID=:ID');
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

        public function deleteDishIngredient($ID_ingredient, $ID_dish)
        {
            $this->db->loadQuery('DELETE FROM DISHES_INGREDIENTS WHERE ID_dishes=:ID_dishes AND ID_ingredients=:ID_ingredients');
            $this->db->bind(':ID_dishes', $ID_dish);
            $this->db->bind(':ID_ingredients', $ID_ingredient);
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