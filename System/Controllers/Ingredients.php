<?php
    namespace System\Controllers;

use System\MainController;


    class Ingredients extends MainController
    {
        
        public function __construct()
        {
            $this->ingredientModel = $this->model('Ingredient');
        }

        /**
         * Default function in address woth fitnessplan
         *
         * @param string $ID
         * @return void
         */
        public function index($ID = '')
        {
            $this->ingredients($ID);
        }

        /**
         * handle request to fetch all dishes
         *
         * @param string $ID - id direct dish
         * @return [json] array/false
         */
        public function ingredients($ID="")
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $ingredientsArr = array();
            $ingredientsArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
              
                    if($ID == "")
                    {
                        if($ingredientsArr['data'] = $this->ingredientModel->getIngredients())
                        {
                            $ingredientsArr['status'] = 'success';
                            echo json_encode($ingredientsArr);
                            die();
                        }
                        else
                        {
                            $ingredientsArr['status'] = 'failed';
                            $ingredientsArr['data'] = ' Składniki nie zostały załadowane poprawnie';
                            echo json_encode($ingredientsArr);
                            die();
                        }
                    }
                    else if(is_numeric($ID))
                    {
                        if($ingredientsArr['data'] = $this->ingredientModel->getIngredient($ID))
                        {
                            $ingredientsArr['status'] = 'success';
                            echo json_encode($ingredientsArr);
                            die();
                        }
                    }
                    else
                    {
                        $ingredientsArr['status'] = 'failed';
                        $ingredientsArr['data']['message'] = 'ID jest niepoprawne';
                    }
                        

                }
            }
            else
            {
                $ingredientsArr['status'] = 'failed';
                $ingredientsArr['data']['message'] = 'Musisz się zalogować';
            }
        }



       /**
         * handle request to fetch all dishes
         *
         * @param string $ID - id direct dish
         * @return [json] array/false
         */
        public function dishingredients($ID)
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $ingredientsArr = array();
            $ingredientsArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
              
                        if($ingredientsArr['data'] = $this->ingredientModel->getIngredientsDish($ID))
                        {
                            $ingredientsArr['status'] = 'success';
                            echo json_encode($ingredientsArr);
                            die();
                        }
                        else
                        {
                            $ingredientsArr['status'] = 'failed';
                            $ingredientsArr['data'] = ' Składniki nie zostały załadowane poprawnie';
                            echo json_encode($ingredientsArr);
                            die();
                        }
                }
            }
            else
            {
                $ingredientsArr['status'] = 'failed';
                $ingredientsArr['data']['message'] = 'Musisz się zalogować';
            }
        }

        /**
         * Add new dishes to database. It can do only logged in user. 
         *
         * @return void
         */
        public function add()
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $data = [
                'name' => '',
                'calories' => '',
                'protein' => '',
                'carbohydrates' => '',
                'fat' => '',
                'fat_full' => '',
                'fat_unfill' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'name_err' => '',
                'calories_err' => '',
                'protein_err' => '',
                'carbohydrates_err' => '',
                'fat_err' => '',
                'fat_full_err' => '',
                'fat_unfill_err' => '',
            ];
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->name) && !empty($dataJson->data->name))
                {
                    $newName = trim(htmlspecialchars($dataJson->data->name));
                    if(isset($dataJson->data->calories) && !empty($dataJson->data->calories) && is_numeric($dataJson->data->calories) && ($dataJson->data->calories >= 0))
                    {
                        $newCalories = trim(htmlspecialchars($dataJson->data->calories));
                        if(isset($dataJson->data->protein) && !empty($dataJson->data->protein) && is_numeric($dataJson->data->protein) && ($dataJson->data->protein >= 0) )
                        {
                            $newProtein = trim(htmlspecialchars($dataJson->data->protein));
                            if(isset($dataJson->data->carbohydrates) && !empty($dataJson->data->carbohydrates) && is_numeric($dataJson->data->carbohydrates) && ($dataJson->data->carbohydrates >= 0))
                            {
                                $newCarbohydrates = trim(htmlspecialchars($dataJson->data->fat));
                                if(isset($dataJson->data->fat) && !empty($dataJson->data->fat) && is_numeric($dataJson->data->fat) && ($dataJson->data->fat >= 0))
                                {
                                    $newFat = trim(htmlspecialchars($dataJson->data->fat));
                                    if(isset($dataJson->data->fat_full) && !empty($dataJson->data->fat_full) && is_numeric($dataJson->data->fat_full) && ($dataJson->data->fat_full >= 0))
                                    {
                                        $newFatFull = trim(htmlspecialchars($dataJson->data->fat_full));
                                        if(isset($dataJson->data->fat_unfill) && !empty($dataJson->data->fat_unfill) && is_numeric($dataJson->data->fat_unfill) && ($dataJson->data->fat_unfill >= 0))
                                        {
                                            $newFatUnfill = trim(htmlspecialchars($dataJson->data->fat_unfill));
                                        }
                                        else
                                        {
                                            $dataFeedback['message'] = 'Proszę wprowadzić ilość tłuszczów nienasyconych';
                                            $dataFeedback['fat_unfill_err'] = 'Liczba tłuszczu nienasyconego powinna być większa od zera.';
                                            echo json_encode($dataFeedback);
                                            die('');
                                        }
                                    }
                                    else
                                    {
                                        $dataFeedback['message'] = 'Proszę wprowadzić ilość tłuszczów nasycoych';
                                        $dataFeedback['fat_full_err'] = 'Liczba tłuszczu nasyconego powinna być większa od zera.';
                                        echo json_encode($dataFeedback);
                                        die('');
                                    }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić ilość tłuszczów';
                                    $dataFeedback['fat_err'] = 'Liczba tłuszczu powinna być większa od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić ilość węglowodanów ';
                                    $dataFeedback['carbohydrates_err'] = 'Ilość węglowodanów powinna być wieksza od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Proszę wprowadzić ilość białka';
                                $dataFeedback['protein_err'] = 'Liczba białka powinna być większa od zera';
                                echo json_encode($dataFeedback);
                                die();
                            }
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Proszę wprowadzić liczbę kalorii';
                            $dataFeedback['calories_err'] = 'Liczba kalori powinna być większa od zera';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Proszę wprowadzić nazwę składnika';
                        $dataFeedback['name_err'] = 'Proszę wprowadzić nazwę składnika';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę się zalogować';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'name' => $newName,
                    'calories' => $newCalories,
                    'protein' => $newProtein,
                    'carbohydrates' => $newCarbohydrates,
                    'fat' => $newFat,
                    'fat_full' => $newFatFull,
                    'fat_unfill' => $newFatUnfill
                ];

                if(empty($dataFeedback['name_err']) && empty($dataFeedback['calories_err']) && empty($dataFeedback['protein_err']) && empty($dataFeedback['carbohydrates_err']) && empty($dataFeedback['fat_err']) && empty($dataFeedback['fat_full']) && empty($dataFeedback['fat_unfill_err']))
                {
                    if($this->ingredientModel->create($data['name'], $data['calories'], $data['protein'], $data['carbohydrates'], $data['fat'], $data['fat_full'], $data['fat_unfill']))
                    {
                        $dataFeedback['message'] = 'Składnik został dodany do bazy składników';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wystąpił błąd podczas dodawania nowego składnika';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        die();
                    }
                }
            }
        

            public function adddishday()
            {
                header('Access-Control-Allow-Origin: *');
                 //header('Content-Type: application/json');
                header('Access-Control-Allow-Methods: POST');
                header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
                $data = [
                    'ID_dishes' => '',
                    'ID_meal' => ''
                ];
    
                $dataFeedback = [
                    'message' => '',
                    'ID_dishes_err' => '',
                    'ID_meal_err' => ''
                ];
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
                {
                    $dataJson = file_get_contents("php://input");
                    $dataJson = json_decode($dataJson);
                    if(isset($dataJson->data->ID_dishes) && !empty($dataJson->data->ID_dishes) && is_numeric($dataJson->data->ID_dishes))
                    {
                        $ID_dishes = trim(htmlspecialchars($dataJson->data->ID_dishes));
                        if(isset($dataJson->data->ID_meal) && !empty($dataJson->data->ID_meal) && is_numeric($dataJson->data->ID_meal) && ($dataJson->data->ID_meal > 0))
                        {
                            $ID_meal = trim(htmlspecialchars($dataJson->data->ID_meal));
                        }
                        else
                        {
                            $dataFeedback['message'] = 'ID posiłku nie jest poprawne';
                            $dataFeedback['ID_meal_err'] = 'ID nie jest poprawne';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'ID dania nie jest poprawne';
                        $dataFeedback['ID_dishes_err'] = 'ID nie jest poprawne';
                        echo json_encode($dataFeedback);
                        die();
                    }
                    
                    $data = [
                        'ID_dishes' => $ID_dishes,
                        'ID_meal' => $ID_meal
                    ];
        
    
                    if(empty($dataFeedback['ID_dishes_err']) && empty($dataFeedback['ID_meal_err']))
                    {
                        if($this->dishesModel->addDishesDay($data['ID_dishes'], $data['ID_meal']))
                        {
                            $dataFeedback['message'] = 'Danie zostało dodane do dań dnia';
                            http_response_code(201);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Wystąpił błąd podczas dodawania nowego dania';
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            die();
                        }
                    }
                }
            }

            public function adddishingredient()
            {
                header('Access-Control-Allow-Origin: *');
                 //header('Content-Type: application/json');
                header('Access-Control-Allow-Methods: POST');
                header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
                $data = [
                    'ID_dishes' => '',
                    'ID_ingredients' => ''
                ];
    
                $dataFeedback = [
                    'message' => '',
                    'ID_dishes_err' => '',
                    'ID_ingredients_err' => ''
                ];
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
                {
                    $dataJson = file_get_contents("php://input");
                    $dataJson = json_decode($dataJson);
                    if(isset($dataJson->data->ID_dishes) && !empty($dataJson->data->ID_dishes) && is_numeric($dataJson->data->ID_dishes))
                    {
                        $ID_dishes = trim(htmlspecialchars($dataJson->data->ID_dishes));
                        if(isset($dataJson->data->ID_ingredients) && !empty($dataJson->data->ID_ingredients) && is_numeric($dataJson->data->ID_ingredients) && ($dataJson->data->ID_ingredients > 0))
                        {
                            $ID_ingredients = trim(htmlspecialchars($dataJson->data->ID_ingredients));
                        }
                        else
                        {
                            $dataFeedback['message'] = 'ID składnika nie jest poprawne';
                            $dataFeedback['ID_meal_err'] = 'ID nie jest poprawne';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'ID dania nie jest poprawne';
                        $dataFeedback['ID_dishes_err'] = 'ID nie jest poprawne';
                        echo json_encode($dataFeedback);
                        die();
                    }
                    
                    $data = [
                        'ID_dishes' => $ID_dishes,
                        'ID_ingredients' => $ID_ingredients,
                    ];
        
    
                    if(empty($dataFeedback['ID_dishes_err']) && empty($dataFeedback['ID_ingredients_err']))
                    {
                        if($this->ingredientModel->addDishesIngredient($data['ID_dishes'], $data['ID_ingredients']))
                        {
                            $dataFeedback['message'] = 'Składnik został dodany do składników dania';
                            http_response_code(201);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Wystąpił błąd podczas dodawania nowego składnika';
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            die();
                        }
                    }
                }
            }

        /**
         * Edit ingredient. It can do only logged in user. 
         *  @param integer $ID - ID record
         * @return void
         */
        public function edit($ID)
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: PUT');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $data = [
                'name' => '',
                'calories' => '',
                'protein' => '',
                'carbohydrates' => '',
                'fat' => '',
                'fat_full' => '',
                'fat_unfill' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'name_err' => '',
                'calories_err' => '',
                'protein_err' => '',
                'carbohydrates_err' => '',
                'fat_err' => '',
                'fat_full_err' => '',
                'fat_unfill_err' => '',
            ];
            if($_SERVER['REQUEST_METHOD'] == 'PUT' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->name) && !empty($dataJson->data->name))
                {
                    $newName = trim(htmlspecialchars($dataJson->data->name));
                    if(isset($dataJson->data->calories) && !empty($dataJson->data->calories) && is_numeric($dataJson->data->calories) && ($dataJson->data->calories >= 0))
                    {
                        $newCalories = trim(htmlspecialchars($dataJson->data->calories));
                        if(isset($dataJson->data->protein) && !empty($dataJson->data->protein) && is_numeric($dataJson->data->protein) && ($dataJson->data->protein >= 0) )
                        {
                            $newProtein = trim(htmlspecialchars($dataJson->data->protein));
                            if(isset($dataJson->data->carbohydrates) && !empty($dataJson->data->carbohydrates) && is_numeric($dataJson->data->carbohydrates) && ($dataJson->data->carbohydrates >= 0))
                            {
                                $newCarbohydrates = trim(htmlspecialchars($dataJson->data->fat));
                                if(isset($dataJson->data->fat) && !empty($dataJson->data->fat) && is_numeric($dataJson->data->fat) && ($dataJson->data->fat >= 0))
                                {
                                    $newFat = trim(htmlspecialchars($dataJson->data->fat));
                                    if(isset($dataJson->data->fat_full) && !empty($dataJson->data->fat_full) && is_numeric($dataJson->data->fat_full) && ($dataJson->data->fat_full >= 0))
                                    {
                                        $newFatFull = trim(htmlspecialchars($dataJson->data->fat_full));
                                        if(isset($dataJson->data->fat_unfill) && !empty($dataJson->data->fat_unfill) && is_numeric($dataJson->data->fat_unfill) && ($dataJson->data->fat_unfill >= 0))
                                        {
                                            $newFatUnfill = trim(htmlspecialchars($dataJson->data->fat_unfill));
                                        }
                                        else
                                        {
                                            $dataFeedback['message'] = 'Proszę wprowadzić ilość tłuszczów nienasyconych';
                                            $dataFeedback['fat_unfill_err'] = 'Liczba tłuszczu nienasyconego powinna być większa od zera.';
                                            echo json_encode($dataFeedback);
                                            die('');
                                        }
                                    }
                                    else
                                    {
                                        $dataFeedback['message'] = 'Proszę wprowadzić ilość tłuszczów nasycoych';
                                        $dataFeedback['fat_full_err'] = 'Liczba tłuszczu nasyconego powinna być większa od zera.';
                                        echo json_encode($dataFeedback);
                                        die('');
                                    }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić ilość tłuszczów';
                                    $dataFeedback['fat_err'] = 'Liczba tłuszczu powinna być większa od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić ilość węglowodanów ';
                                    $dataFeedback['carbohydrates_err'] = 'Ilość węglowodanów powinna być wieksza od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Proszę wprowadzić ilość białka';
                                $dataFeedback['protein_err'] = 'Liczba białka powinna być większa od zera';
                                echo json_encode($dataFeedback);
                                die();
                            }
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Proszę wprowadzić liczbę kalorii';
                            $dataFeedback['calories_err'] = 'Liczba kalori powinna być większa od zera';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Proszę wprowadzić nazwę składnika';
                        $dataFeedback['name_err'] = 'Proszę wprowadzić nazwę składnika';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę się zalogować';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'name' => $newName,
                    'calories' => $newCalories,
                    'protein' => $newProtein,
                    'carbohydrates' => $newCarbohydrates,
                    'fat' => $newFat,
                    'fat_full' => $newFatFull,
                    'fat_unfill' => $newFatUnfill
                ];
                
                if(empty($dataFeedback['name_err']) && empty($dataFeedback['calories_err']) && empty($dataFeedback['protein_err']) && empty($dataFeedback['carbohydrates_err']) && empty($dataFeedback['fat_err']) && empty($dataFeedback['fat_full']) && empty($dataFeedback['fat_unfill_err']))
                {
                    if($this->ingredientModel->edit($data['name'], $data['calories'], $data['protein'], $data['carbohydrates'], $data['fat'], $data['fat_full'], $data['fat_unfill'], $ID))
                    {
                        $dataFeedback['message'] = 'Składnik został zaktualizowany';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wystąpił błąd podczas aktualizacji składnika';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        die();
                    }
                }
            }
        

        public function delete($ID)
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               if(is_numeric($ID))
               {
                   if($this->ingredientModel->delete($ID))
                   {
                       $dataFeedback['message'] = 'Składnik został poprawnie usunięty';
                       $dataFeedback['status'] = 'success';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas usuwania składnika';
                       $dataFeedback['status'] = 'failed';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       die();
                   }
               }
               else
               {
                $dataFeedback['message'] = 'ID jest niepoprawne';
                $dataFeedback['status'] = 'failed';
                http_response_code(200);
                $dataJson = json_encode($dataFeedback);
                die();
               }
           }
           else
           {
            $dataFeedback['message'] = 'Używasz niedozwolonej metody lub nie jesteś zalogowany';
            $dataFeedback['status'] = 'failed';
            http_response_code(200);
            $dataJson = json_encode($dataFeedback);
            die();
           }
        }

        
        /**
         * Delete dishes
         *
         * @param [type] $ID_dishes
         * @param [type] $ID_ingredient
         * @return void
         */
        public function deletedishingredient($ID_ingredient, $ID_dishes)
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               if(is_numeric($ID_dishes) && is_numeric($ID_ingredient))
               {
                   if($this->ingredientModel->deleteDishIngredient($ID_ingredient, $ID_dishes))
                   {
                       $dataFeedback['message'] = 'Składnik został usunięty ze składników dania.';
                       $dataFeedback['status'] = 'success';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas usuwania składnika';
                       $dataFeedback['status'] = 'failed';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       die();
                   }
               }
               else
               {
                $dataFeedback['message'] = 'ID jest niepoprawne';
                $dataFeedback['status'] = 'failed';
                http_response_code(200);
                $dataJson = json_encode($dataFeedback);
                die();
               }
           }
           else
           {
            $dataFeedback['message'] = 'Używasz niedozwolonej metody lub nie jesteś zalogowany';
            $dataFeedback['status'] = 'failed';
            http_response_code(200);
            $dataJson = json_encode($dataFeedback);
            die();
           }
        }

               /**
         * Delete dishes form dish day board
         *
         * @param [type] $ID_dishes
         * @return void
         */
        public function deletedishday($ID_dishes)
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               if(is_numeric($ID_dishes))
               {
                   if($this->dishesModel->deleteDishDay($ID_dishes))
                   {
                       $dataFeedback['message'] = 'Danie zostało usunięte z jadłospisu dnia.';
                       $dataFeedback['status'] = 'success';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas usuwania dania';
                       $dataFeedback['status'] = 'failed';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       die();
                   }
               }
               else
               {
                $dataFeedback['message'] = 'ID jest niepoprawne';
                $dataFeedback['status'] = 'failed';
                http_response_code(200);
                $dataJson = json_encode($dataFeedback);
                die();
               }
           }
           else
           {
            $dataFeedback['message'] = 'Używasz niedozwolonej metody lub nie jesteś zalogowany';
            $dataFeedback['status'] = 'failed';
            http_response_code(200);
            $dataJson = json_encode($dataFeedback);
            die();
           }
        }

                       /**
         * Delete dishes
         *
         * @param [type] $ID_dishes
         * @return void
         */
        public function deleteplandish($ID_dishes)
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               if(is_numeric($ID_dishes))
               {
                   if($this->dishesModel->deleteFitnessPlanDish($ID_dishes))
                   {
                       $dataFeedback['message'] = 'Danie zostało usunięte z jadłospisu planu.';
                       $dataFeedback['status'] = 'success';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas usuwania dania';
                       $dataFeedback['status'] = 'failed';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       die();
                   }
               }
               else
               {
                $dataFeedback['message'] = 'ID jest niepoprawne';
                $dataFeedback['status'] = 'failed';
                http_response_code(200);
                $dataJson = json_encode($dataFeedback);
                die();
               }
           }
           else
           {
            $dataFeedback['message'] = 'Używasz niedozwolonej metody lub nie jesteś zalogowany';
            $dataFeedback['status'] = 'failed';
            http_response_code(200);
            $dataJson = json_encode($dataFeedback);
            die();
           }
        }
    }
?>