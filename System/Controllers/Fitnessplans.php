<?php
    namespace System\Controllers;

use System\MainController;


    class Fitnessplans extends MainController
    {
        
        public function __construct()
        {
            $this->fitnessPlanModel = $this->model('Fitnessplan');
        }

        /**
         * Default function in address woth fitnessplan
         *
         * @param string $ID
         * @return void
         */
        public function index($ID = 'all')
        {
            $this->fitnessplans($ID);
        }

        /**
         * handle request to fetch all fitnessplans which user is signed
         *
         * @param string $ID - fitness plan
         * @return [json] array/false
         */
        public function userfitnessplans()
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $fitnessPlansArr = array();
            $fitnessPlansArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
              
                        if($fitnessPlansArr['data'] = $this->fitnessPlanModel->getFitnessPlansUserConsument())
                        {
                            $fitnessPlansArr['status'] = 'success';
                            echo json_encode($fitnessPlansArr);
                        }
                        else
                        {
                            $fitnessPlansArr['status'] = 'failed';
                            $fitnessPlansArr['data'] = ' Plany nie zostały załadowane poprawnie';
                            echo json_encode($fitnessPlansArr);
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
         * handle request to fetch all fitnessplans which user is signed
         *
         * @param string $ID - fitness plan
         * @return [json] array/false
         */
        public function authorfitnessplans($ID="")
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $fitnessPlansArr = array();
            $fitnessPlansArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
                        if(is_numeric($ID) && isset($ID))
                        {
                            if($fitnessPlansArr['data'] = $this->fitnessPlanModel->getFitnessPlansAuthor($ID))
                            {
                                $fitnessPlansArr['status'] = 'success';
                                echo json_encode($fitnessPlansArr);
                            }
                            else
                            {
                                $fitnessPlansArr['status'] = 'failed';
                                $fitnessPlansArr['data'] = ' Plany nie zostały załadowane poprawnie';
                                echo json_encode($fitnessPlansArr);
                            }
                        }
                        else
                        {
                            $fitnessPlansArr['status'] = 'failed';
                                $fitnessPlansArr['data'] = ' Niepoprawne ID';
                                echo json_encode($fitnessPlansArr);
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
         * Get all fitness plans from database or specific record
         *
         * @param string $ID - ID specific plans
         * @return void
         */
        public function fitnessplans($ID = 'all')
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $fitnessPlansArr = array();
            $fitnessPlansArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
                    if($ID === 'all')
                    {
                        if($fitnessPlansArr['data'] = $this->fitnessPlanModel->getFitnessPlans())
                        {
                            $fitnessPlansArr['status'] = 'success';
                            echo json_encode($fitnessPlansArr);
                        }
                        else
                        {
                            $fitnessPlansArr['status'] = 'failed';
                            $fitnessPlansArr['data'] = ' Plany nie zostały załadowane poprawnie';
                            echo json_encode($fitnessPlansArr);
                        }
                    }
                    else if(is_numeric($ID))
                    {
                        if($fitnessPlansArr['data'] = $this->fitnessPlanModel->getFitnessPlanByID($ID))
                        {
                            $fitnessPlansArr['status'] = 'success';
                            echo json_encode($fitnessPlansArr);
                        }
                        else
                        {
                            $fitnessPlansArr['status'] = 'failed';
                            $fitnessPlansArr['data'] = ' Plan nie znaleziony';
                            echo json_encode($fitnessPlansArr);
                        }
                    }
                    else
                    {
                        $fitnessPlansArr['status'] = 'failed';
                        $fitnessPlansArr['data'] = ' Niepoprawne ID';
                        echo json_encode($fitnessPlansArr);
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
         * Add new plan to database. It can do only logged in user. 
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
                'ID_author' => '',
                'type' => '',
                'tags' => '',
                'description' => '',
                'daily_protein' => '',
                'daily_carbohydrates' => '',
                'daily_fat' => '',
                'num_dishes' => '',
                'energy_aim' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'name_err' => '',
                'type_err' => '',
                'tags_err' => '',
                'description_err' => '',
                'daily_protein_err' => '',
                'daily_carbohydrates_err' => '',
                'daily_fat_err' => '',
                'num_dishes_err' => '',
                'energy_aim_err' => ''
            ];
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->name) && !empty($dataJson->data->name))
                {
                    $newName = trim(htmlspecialchars($dataJson->data->name));
                    if(isset($dataJson->data->type) && !empty($dataJson->data->type))
                    {
                        $newType = trim(htmlspecialchars($dataJson->data->type));
                        if(isset($dataJson->data->tags) && !empty($dataJson->data->tags) )
                        {
                            $newTags = trim(htmlspecialchars($dataJson->data->tags));
                            if(isset($dataJson->data->description) && !empty($dataJson->data->description))
                            {
                                $newDescription = trim(htmlspecialchars($dataJson->data->description));
                                if(isset($dataJson->data->daily_protein) && !empty($dataJson->data->daily_protein) && is_numeric($dataJson->data->daily_protein) && ($dataJson->data->daily_protein > 0))
                                {
                                    $newDailyProtein = trim(htmlspecialchars($dataJson->data->daily_protein));
                                    if(isset($dataJson->data->daily_carbohydrates) && !empty($dataJson->data->daily_carbohydrates) && is_numeric($dataJson->data->daily_carbohydrates) && ($dataJson->data->daily_carbohydrates > 0))
                                    {
                                        $newDailyCarbohydrates = trim(htmlspecialchars($dataJson->data->daily_carbohydrates));
                                        if(isset($dataJson->data->daily_fat) && !empty($dataJson->data->daily_fat) && is_numeric($dataJson->data->daily_fat) && ($dataJson->data->daily_fat > 0))
                                        {
                                            $newDailyFat= trim(htmlspecialchars($dataJson->data->daily_fat));
                                            if(isset($dataJson->data->num_dishes) && !empty($dataJson->data->num_dishes) && is_numeric($dataJson->data->num_dishes) && ($dataJson->data->num_dishes > 0))
                                            {
                                                $newNumDishes = trim(htmlspecialchars($dataJson->data->num_dishes));
                                            }
                                            else
                                            {
                                                $dataFeedback['message'] = 'Proszę wprowadź liczbę dań.';
                                                $dataFeedback['num_dishes_err'] = 'Liczba dań jest pusta. Liczba dań musi być wieksza od zera.';
                                                echo json_encode($dataFeedback);
                                                die('');
                                            }
                                        }
                                        else
                                        {
                                            $dataFeedback['message'] = 'Proszę wprowadź liczbę tłuszczy spożywanychw  diecie.';
                                            $dataFeedback['daily_fat_err'] = 'Liczba tłuszczy musi być większa od zera.';
                                            echo json_encode($dataFeedback);
                                            die('');
                                        }
                                    }
                                    else
                                    {
                                        $dataFeedback['message'] = 'Proszę wprowadź liczbę węglowodanów';
                                        $dataFeedback['daily_carbohydrates_err'] = 'Liczba spożywanych węglowodanów musi być większa od zera.';
                                        echo json_encode($dataFeedback);
                                        die('');
                                    }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić liczbę białka';
                                    $dataFeedback['daily_protein_err'] = 'Liczba spożywanego białka musi być większa od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Proszę wprowadzić opis';
                                $dataFeedback['description_err'] = 'Opis planu jest pusty';
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
                        $dataFeedback['message'] = 'Proszę uzupełnić pola';
                        $dataFeedback['type_err'] = 'Proszę wprowadzić typ planu';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę uzupełnić pola';
                    $dataFeedback['name_err'] = 'Proszę wprowadzić nazwę';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'name' => $newName,
                    'ID_author' => $_SESSION['user_id'],
                    'type' => $newType,
                    'tags' => $newTags,
                    'description' => $newDescription,
                    'daily_protein' => $newDailyProtein,
                    'daily_carbohydrates' => $newDailyCarbohydrates,
                    'daily_fat' => $newDailyFat,
                    'num_dishes' => $newNumDishes
                ];

                if(empty($dataFeedback['name_err']) && empty($dataFeedback['type_err']) && empty($dataFeedback['tags_err']) && empty($dataFeedback['description_err']) && empty($dataFeedback['daily_protein_err']) && empty($dataFeedback['daily_carbohydrates_err']) && empty($dataFeedback['daily_fat_err']) && empty($data['num_dishes_err']))
                {
                    if($this->fitnessPlanModel->create($data['name'], $data['ID_author'], $data['type'], $data['tags'], $data['description'], $data['daily_protein'], $data['daily_carbohydrates'], $data['daily_fat'], $data['num_dishes']))
                    {
                        $dataFeedback['message'] = 'Plan został utworzony';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wystąpił błąd podczas tworzenia nowego planu';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        die();
                    }
                }
            }
        }

        /**
         * Edit plan. It can do only logged in user. 
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
                'ID_author' => '',
                'type' => '',
                'tags' => '',
                'description' => '',
                'daily_protein' => '',
                'daily_carbohydrates' => '',
                'daily_fat' => '',
                'num_dishes' => '',
                'energy_aim' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'name_err' => '',
                'type_err' => '',
                'tags_err' => '',
                'description_err' => '',
                'daily_protein_err' => '',
                'daily_carbohydrates_err' => '',
                'daily_fat_err' => '',
                'num_dishes_err' => '',
                'energy_aim_err' => ''
            ];
            if($_SERVER['REQUEST_METHOD'] == 'PUT' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->name) && !empty($dataJson->data->name))
                {
                    $newName = trim(htmlspecialchars($dataJson->data->name));
                    if(isset($dataJson->data->type) && !empty($dataJson->data->type))
                    {
                        $newType = trim(htmlspecialchars($dataJson->data->type));
                        if(isset($dataJson->data->tags) && !empty($dataJson->data->tags) )
                        {
                            $newTags = trim(htmlspecialchars($dataJson->data->tags));
                            if(isset($dataJson->data->description) && !empty($dataJson->data->description))
                            {
                                $newDescription = trim(htmlspecialchars($dataJson->data->description));
                                if(isset($dataJson->data->daily_protein) && !empty($dataJson->data->daily_protein) && is_numeric($dataJson->data->daily_protein) && ($dataJson->data->daily_protein > 0))
                                {
                                    $newDailyProtein = trim(htmlspecialchars($dataJson->data->daily_protein));
                                    if(isset($dataJson->data->daily_carbohydrates) && !empty($dataJson->data->daily_carbohydrates) && is_numeric($dataJson->data->daily_carbohydrates) && ($dataJson->data->daily_carbohydrates > 0))
                                    {
                                        $newDailyCarbohydrates = trim(htmlspecialchars($dataJson->data->daily_carbohydrates));
                                        if(isset($dataJson->data->daily_fat) && !empty($dataJson->data->daily_fat) && is_numeric($dataJson->data->daily_fat) && ($dataJson->data->daily_fat > 0))
                                        {
                                            $newDailyFat= trim(htmlspecialchars($dataJson->data->daily_fat));
                                            if(isset($dataJson->data->num_dishes) && !empty($dataJson->data->num_dishes) && is_numeric($dataJson->data->num_dishes) && ($dataJson->data->num_dishes > 0))
                                            {
                                                $newNumDishes = trim(htmlspecialchars($dataJson->data->num_dishes));
                                            }
                                            else
                                            {
                                                $dataFeedback['message'] = 'Proszę wprowadź liczbę dań.';
                                                $dataFeedback['num_dishes_err'] = 'Liczba dań jest pusta. Liczba dań musi być wieksza od zera.';
                                                echo json_encode($dataFeedback);
                                                die('');
                                            }
                                        }
                                        else
                                        {
                                            $dataFeedback['message'] = 'Proszę wprowadź liczbę tłuszczy spożywanychw  diecie.';
                                            $dataFeedback['daily_fat_err'] = 'Liczba tłuszczy musi być większa od zera.';
                                            echo json_encode($dataFeedback);
                                            die('');
                                        }
                                    }
                                    else
                                    {
                                        $dataFeedback['message'] = 'Proszę wprowadź liczbę węglowodanów';
                                        $dataFeedback['daily_carbohydrates_err'] = 'Liczba spożywanych węglowodanów musi być większa od zera.';
                                        echo json_encode($dataFeedback);
                                        die('');
                                    }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Proszę wprowadzić liczbę białka';
                                    $dataFeedback['daily_protein_err'] = 'Liczba spożywanego białka musi być większa od zera.';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Proszę wprowadzić opis';
                                $dataFeedback['description_err'] = 'Opis planu jest pusty';
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
                        $dataFeedback['message'] = 'Proszę uzupełnić pola';
                        $dataFeedback['type_err'] = 'Proszę wprowadzić typ planu';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę uzupełnić pola';
                    $dataFeedback['name_err'] = 'Proszę wprowadzić nazwę';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'name' => $newName,
                    'ID_author' => $_SESSION['user_id'],
                    'type' => $newType,
                    'tags' => $newTags,
                    'description' => $newDescription,
                    'daily_protein' => $newDailyProtein,
                    'daily_carbohydrates' => $newDailyCarbohydrates,
                    'daily_fat' => $newDailyFat,
                    'num_dishes' => $newNumDishes
                ];

                if(empty($dataFeedback['name_err']) && empty($dataFeedback['type_err']) && empty($dataFeedback['tags_err']) && empty($dataFeedback['description_err']) && empty($dataFeedback['daily_protein_err']) && empty($dataFeedback['daily_carbohydrates_err']) && empty($dataFeedback['daily_fat_err']) && empty($data['num_dishes_err']))
                {
                    if(is_numeric($ID))
                    {
                        if($this->fitnessPlanModel->edit($ID, $data['name'], $data['ID_author'], $data['type'], $data['tags'], $data['description'], $data['daily_protein'], $data['daily_carbohydrates'], $data['daily_fat'], $data['num_dishes']))
                        {
                            $dataFeedback['message'] = 'Plan został zmodyfikowany';
                            http_response_code(201);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Wystąpił błąd podczas aktualizowania planu';
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'ID jest niepoprawne';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        die();
                    }

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
         * Add new plan to users list. It can do only logged in user. 
         *
         * @return void
         */
        public function addplantouserplans()
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $data = [
                'ID_fitness_plan' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'ID_fitness_plan_err' => ''
            ];
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->ID_fitness_plan) && !empty($dataJson->data->ID_fitness_plan) && is_numeric($dataJson->data->ID_fitness_plan))
                {
                    $newIDFitnessPlan = trim(htmlspecialchars($dataJson->data->ID_fitness_plan));
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę uzupełnić pola';
                    $dataFeedback['ID_fitness_plan_err'] = 'Proszę wprowadzić ID planu';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'ID_fitness_plan' => $newIDFitnessPlan
                ];

                if(empty($dataFeedback['ID_fitness_plan_err']))
                {
                    if($this->fitnessPlanModel->signUsersToFitnessPlan($data['ID_fitness_plan']))
                    {
                        $dataFeedback['message'] = 'Plan został dodany do zbiorów użytkownika';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wystąpił błąd dołączania do nowego planu';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        die();
                    }
                }
            }
        }

        /**
         * Delete fitness plan form user set
         *
         * @param [type] $ID_fitness_plan - fitness plan
         * @return void
         */
        public function deletefitnessplanfromuser($ID_fitness_plan)
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               if(is_numeric($ID_fitness_plan))
               {
                   if($this->fitnessPlanModel->deleteFitnessPlanFromUser($ID_fitness_plan))
                   {
                       $dataFeedback['message'] = 'Plan został poprawnie usunięty z zapisanych planów użytkownika';
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
    }
?>