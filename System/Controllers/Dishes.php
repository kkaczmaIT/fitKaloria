<?php
    namespace System\Controllers;

use System\MainController;


    class Dishes extends MainController
    {
        
        public function __construct()
        {
            $this->dishesModel = $this->model('Dish');
        }

        /**
         * Default function in address woth fitnessplan
         *
         * @param string $ID
         * @return void
         */
        public function index($ID = '')
        {
            $this->dishes($ID);
        }

        /**
         * handle request to fetch all dishes
         *
         * @param string $ID - id direct dish
         * @return [json] array/false
         */
        public function dishes($ID="")
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $dishesArr = array();
            $dishesArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
              
                    if($ID == "")
                    {
                        if($dishesArr['data'] = $this->dishesModel->getDishes())
                        {
                            $dishesArr['status'] = 'success';
                            echo json_encode($dishesArr);
                        }
                        else
                        {
                            $dishesArr['status'] = 'failed';
                            $dishesArr['data'] = ' Dania nie zostały załadowane poprawnie';
                            echo json_encode($dishesArr);
                            die();
                        }
                    }
                    else if(is_numeric($ID))
                    {
                        if($dishesArr['data'] = $this->dishesModel->getDish($ID))
                        {
                            $dishesArr['status'] = 'success';
                            echo json_encode($dishesArr);
                            die();
                        }
                    }
                    else
                    {
                        $dishesArr['status'] = 'failed';
                        $dishesArr['data']['message'] = 'ID jest niepoprawne';
                    }
                        

                }
            }
            else
            {
                $userArr['status'] = 'failed';
                $userArr['data']['message'] = 'Musisz się zalogować';
            }
        }

        public function userdishesdaymeal($year, $month, $day, $ID_meal)
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $dishesArr = array();
            $dishesArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
                    if(empty($year) && empty($month) && empty($day) && empty($ID_meal))
                    {
                        $newYear = date('Y');
                        $newMonth = date('M');
                        $newDay = date('D');
                        $newID_meal = 1;
                    }
                    else
                    {
                        $newYear = $year;
                        $newMonth = $month;
                        $newDay = $day;
                        $newID_meal = $ID_meal;
                    }
                    if(is_numeric($year) && is_numeric($month) && is_numeric($day) && is_numeric($ID_meal))
                    {
                        if($dishesArr['data'] = $this->dishesModel->getUserDishesFromDay($newYear, $newMonth, $newDay, $newID_meal))
                        {
                            $dishesArr['status'] = 'success';
                            echo json_encode($dishesArr);
                        }
                        else
                        {
                            $dishesArr['status'] = 'failed';
                            $dishesArr['data'] = 'Dania z dnia nie zostały załadowane poprawnie ';
                            echo json_encode($dishesArr);
                            die();
                        }
                    }
                    else
                    {
                        $dishesArr['status'] = 'failed';
                        $dishesArr['data']['message'] = 'Data jest niepoprawna';
                    }
                        

                }
            }
            else
            {
                $userArr['status'] = 'failed';
                $userArr['data']['message'] = 'Musisz się zalogować';
            }
        }

        public function fitnessplandishes($ID_fitness_plan, $ID_meal)
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $dishesArr = array();
            $dishesArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
                    if(empty($ID_fitness_plan) && empty($ID_meal))
                    {
                        $dishesArr['status'] = 'failed';
                        $dishesArr['data'] = 'Dania z planu nie zostały załadowane poprawnie ';
                        echo json_encode($dishesArr);
                        die();
                    }
                    else
                    {
                        $newIDFitnessPlan = $ID_fitness_plan;
                        $newID_meal = $ID_meal;
                    }
                    if(is_numeric($newIDFitnessPlan) && is_numeric($newID_meal))
                    {
                        if($dishesArr['data'] = $this->dishesModel->getFitnessPlanDishesMealType($newIDFitnessPlan, $newID_meal))
                        {
                            $dishesArr['status'] = 'success';
                            echo json_encode($dishesArr);
                        }
                        else
                        {
                            $dishesArr['status'] = 'failed';
                            $dishesArr['data'] = 'Dania z planu nie zostały załadowane poprawnie ';
                            echo json_encode($dishesArr);
                            die();
                        }
                    }
                    else
                    {
                        $dishesArr['status'] = 'failed';
                        $dishesArr['data']['message'] = 'Data jest niepoprawna';
                    }
                        

                }
            }
            else
            {
                $userArr['status'] = 'failed';
                $userArr['data']['message'] = 'Musisz się zalogować';
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
                'amount_calories' => '',
                'amount_portion' => '',
                'recommend_portion' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'name_err' => '',
                'amount_calories_err' => '',
                'amount_portion_err' => '',
                'recommend_portion_err' => ''
            ];
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->name) && !empty($dataJson->data->name))
                {
                    $newName = trim(htmlspecialchars($dataJson->data->name));
                    if(isset($dataJson->data->amount_calories) && !empty($dataJson->data->amount_calories) && is_numeric($dataJson->data->amount_calories) && ($dataJson->data->amount_calories > 0))
                    {
                        $newAmountCalories = trim(htmlspecialchars($dataJson->data->amount_calories));
                        if(isset($dataJson->data->amount_portion) && !empty($dataJson->data->amount_portion) && is_numeric($dataJson->data->amount_portion) && ($dataJson->data->amount_portion > 0) )
                        {
                            $newAmountPortion = trim(htmlspecialchars($dataJson->data->amount_portion));
                            if(isset($dataJson->data->recommend_portion) && !empty($dataJson->data->recommend_portion) && is_numeric($dataJson->data->recommend_portion) && ($dataJson->data->recommend_portion > 0))
                            {
                                $newRecommendPortion = trim(htmlspecialchars($dataJson->data->recommend_portion));
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić liczbę zalecanych porcji spożycia w ciągu dnia';
                                    $dataFeedback['recommend_portion_err'] = 'Liczba zalecanych posiłków powinna być większa od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Proszę wprowadzić liczbę porcji';
                                $dataFeedback['amount_portion_err'] = 'Liczba porcji powinna być większa od zera';
                                echo json_encode($dataFeedback);
                                die();
                            }
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Proszę wprowadzić tagi';
                            $dataFeedback['tags_err'] = 'Proszę wprowadzić tagi';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Proszę liczbę kalorii';
                        $dataFeedback['amount_calories_err'] = 'Liczba kalori musi być większa od zera';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę uzupełnić pola';
                    $dataFeedback['name_err'] = 'Proszę wprowadzić nazwę dania';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'name' => $newName,
                    'amount_calories' => $newAmountCalories,
                    'amount_portion' => $newAmountPortion,
                    'recommend_portion' => $newRecommendPortion
                ];

                if(empty($dataFeedback['name_err']) && empty($dataFeedback['amount_calories_err']) && empty($dataFeedback['amount_portion_err']) && empty($dataFeedback['recommend_portion_err']))
                {
                    if($this->dishesModel->create($data['name'], $data['amount_calories'], $data['amount_portion'], $data['recommend_portion']))
                    {
                        $dataFeedback['message'] = 'Danie zostało dodane do bazy dań';
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

            public function adddishtofitnessplan()
            {
                header('Access-Control-Allow-Origin: *');
                 //header('Content-Type: application/json');
                header('Access-Control-Allow-Methods: POST');
                header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
                $data = [
                    'ID_dishes' => '',
                    'ID_meal' => '',
                    'ID_fitness_plan' => ''
                ];
    
                $dataFeedback = [
                    'message' => '',
                    'ID_dishes_err' => '',
                    'ID_meal_err' => '',
                    'ID_fitness_plan_err' => ''
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
                            if(isset($dataJson->data->ID_fitness_plan) && !empty($dataJson->data->ID_fitness_plan) && is_numeric($dataJson->data->ID_fitness_plan) && ($dataJson->data->ID_fitness_plan > 0))
                            {
                                $ID_fitness_plan = trim(htmlspecialchars($dataJson->data->ID_fitness_plan));
                            }
                            else
                            {
                                $dataFeedback['message'] = 'ID planu nie jest poprawne';
                                $dataFeedback['ID_fitness_plan_err'] = 'ID nie jest poprawne';
                                echo json_encode($dataFeedback);
                                die();
                            }
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
                        'ID_meal' => $ID_meal,
                        'ID_fitness_plan' => $ID_fitness_plan
                    ];
        
    
                    if(empty($dataFeedback['ID_dishes_err']) && empty($dataFeedback['ID_meal_err']) && empty($dataFeedback['ID_fitness_plan']))
                    {
                        if($this->dishesModel->addFitnessPlanDishes($data['ID_dishes'], $data['ID_meal'], $data['ID_fitness_plan']))
                        {
                            $dataFeedback['message'] = 'Danie zostało dodane do dań planu';
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

        /**
         * Edit dishes. It can do only logged in user. 
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
                'amount_calories' => '',
                'amount_portion' => '',
                'recommend_portion' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'name_err' => '',
                'amount_calories_err' => '',
                'amount_portion_err' => '',
                'recommend_portion_err' => ''
            ];
            if($_SERVER['REQUEST_METHOD'] == 'PUT' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->name) && !empty($dataJson->data->name))
                {
                    $newName = trim(htmlspecialchars($dataJson->data->name));
                    if(isset($dataJson->data->amount_calories) && !empty($dataJson->data->amount_calories) && is_numeric($dataJson->data->amount_calories) && ($dataJson->data->amount_calories > 0))
                    {
                        $newAmountCalories = trim(htmlspecialchars($dataJson->data->amount_calories));
                        if(isset($dataJson->data->amount_portion) && !empty($dataJson->data->amount_portion) && is_numeric($dataJson->data->amount_portion) && ($dataJson->data->amount_portion > 0) )
                        {
                            $newAmountPortion = trim(htmlspecialchars($dataJson->data->amount_portion));
                            if(isset($dataJson->data->recommend_portion) && !empty($dataJson->data->recommend_portion) && is_numeric($dataJson->data->recommend_portion) && ($dataJson->data->recommend_portion > 0))
                            {
                                $newRecommendPortion = trim(htmlspecialchars($dataJson->data->recommend_portion));
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić liczbę zalecanych porcji spożycia w ciągu dnia';
                                    $dataFeedback['recommend_portion_err'] = 'Liczba zalecanych posiłków powinna być większa od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Proszę wprowadzić liczbę porcji';
                                $dataFeedback['amount_portion_err'] = 'Liczba porcji powinna być większa od zera';
                                echo json_encode($dataFeedback);
                                die();
                            }
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Proszę wprowadzić tagi';
                            $dataFeedback['tags_err'] = 'Proszę wprowadzić tagi';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Proszę liczbę kalorii';
                        $dataFeedback['amount_calories_err'] = 'Liczba kalori musi być większa od zera';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę uzupełnić pola';
                    $dataFeedback['name_err'] = 'Proszę wprowadzić nazwę dania';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'name' => $newName,
                    'amount_calories' => $newAmountCalories,
                    'amount_portion' => $newAmountPortion,
                    'recommend_portion' => $newRecommendPortion
                ];
                
                
                if(empty($dataFeedback['name_err']) && empty($dataFeedback['amount_calories_err']) && empty($dataFeedback['amount_portion_err']) && empty($dataFeedback['recommend_portion_err']))
                {
                    if($this->dishesModel->edit($data['name'], $data['amount_calories'], $data['amount_portion'], $data['recommend_portion'], $ID))
                    {
                        $dataFeedback['message'] = 'Danie zostało zaktualizowane';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wystąpił błąd podczas aktualizacji dania';
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
                   if($this->fitnessPlanModel->delete($ID))
                   {
                       $dataFeedback['message'] = 'Plan został poprawnie usunięty';
                       $dataFeedback['status'] = 'success';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas usuwania planu';
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
        public function deletedish($ID_dishes)
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               if(is_numeric($ID_dishes))
               {
                   if($this->dishesModel->delete($ID_dishes))
                   {
                       $dataFeedback['message'] = 'Danie zostało usunięte.';
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