<?php
    namespace System\Controllers;

use System\MainController;


    class Users extends MainController
    {
        
        public function __construct()
        {
            $this->userModel = $this->model('User');
        }

        public function index($ID = 'all')
        {
            $this->users($ID);
        }


        /**
         * Register new user. Add new user to database.
         * Require user model
         * Permission is constant. Do not use permission in project for now.
         * Receive POST request with json
         * @return void
         */
        public function registerUser()
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $data = [
                'login' => '',
                'email' => '',
                'firstname' => '',
                'lastname' => '',
                'password' => '',
                'confirm_password' => '',
                'weight' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'login_err' => '',
                'email_err' => '',
                'firstname_err' => '',
                'lastname_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'weight_err' => ''
            ];
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->login) && !empty($dataJson->data->login))
                {
                    $newLogin = trim(htmlspecialchars($dataJson->data->login));
                    if(isset($dataJson->data->password) && !empty($dataJson->data->password) &&  (strlen($dataJson->data->password) >= 8))
                    {
                        $newPassword = trim(htmlspecialchars($dataJson->data->password));
                        if(isset($dataJson->data->password_confirm) && !empty($dataJson->data->password_confirm) &&  (strlen($dataJson->data->password_confirm) >= 8))
                        {
                            $newConfirmPassword = trim(htmlspecialchars($dataJson->data->password_confirm));
                            if(isset($dataJson->data->email) && !empty($dataJson->data->email))
                            {
                                $newEmail = trim(htmlspecialchars(filter_var($dataJson->data->email, FILTER_SANITIZE_EMAIL)));
                                if(isset($dataJson->data->firstname) && !empty($dataJson->data->firstname))
                                {
                                    $newFirstname = trim(htmlspecialchars($dataJson->data->firstname));
                                    if(isset($dataJson->data->lastname) && !empty($dataJson->data->lastname))
                                    {
                                        $newLastname = trim(htmlspecialchars($dataJson->data->lastname));
                                        if(isset($dataJson->data->weight) && !empty($dataJson->data->weight) && is_numeric($dataJson->data->weight) && ($dataJson->data->weight > 0))
                                        {
                                            $newWeight = trim(htmlspecialchars($dataJson->data->weight));
                                        }
                                        else
                                        {
                                            $dataFeedback['message'] = 'Prosz?? wprowad?? wag??.';
                                            $dataFeedback['weight_err'] = 'Waga musi by?? wi??ksza od zera';
                                            echo json_encode($dataFeedback);
                                            die('');
                                        }
                                    }
                                    else
                                    {
                                        $dataFeedback['message'] = 'Wprowad?? nazwisko';
                                        $dataFeedback['lastname_err'] = 'Pole nazwisko jest puste';
                                        echo json_encode($dataFeedback);
                                        die('');
                                    }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Wprowad?? imi??';
                                    $dataFeedback['firstname_err'] = 'Pole imi?? jest puste';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Wprowad?? adres email';
                                $dataFeedback['email_err'] = 'Pole na adres email jest puste';
                                echo json_encode($dataFeedback);
                                die();
                            }
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Prosz?? wprowad?? has??o jeszcze raz';
                            $dataFeedback['password_confirm_err'] = 'Has??o powinno sk??ada?? si?? z conajmniej o??miu znak??w';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wprowad?? has??o';
                        $dataFeedback['password_err'] = 'Pole has??o jest puste. Has??o musi si?? sk??ada?? z conajmniej o??miu znak??w';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Prosz?? wprowad?? nazw?? u??ytkownika';
                    $dataFeedback['login_err'] = 'Login jest pusty';
                    echo json_encode($dataFeedback);
                    die();
                }
                
                $data = [
                    'login' => $newLogin,
                    'email' => $newEmail,
                    'firstname' => $newFirstname,
                    'lastname' => $newLastname,
                    'password' => $newPassword,
                    'confirm_password' => $newConfirmPassword,
                    'weight' => $newWeight
                ];


                    if($this->userModel->findUserByLogin($data['login']))
                    {
                        $dataFeedback['login_err'] = 'Login jest u??yty przez innego u??ytkownika';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }

                    if($this->userModel->findUserByEmail($data['email']))
                    {
                        $dataFeedback['email_err'] = 'Email jest u??yty przez innego u??ytkownika';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }

                    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $dataFeedback['email_err'] = 'Email jest niepoprawny';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }

                if (empty($data['password']))
                {
                    $dataFeedback['password_err'] = 'Prosz?? wprowad?? has??o';
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    die();
                }

                if(strcmp($data['password'], $data['confirm_password']))
                {
                    $dataFeedback['confirm_password_err'] = 'Has??a si?? r????ni??';
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    die();
                }

                $dataFeedback = [
                    'message' => '',
                    'login_err' => '',
                    'email_err' => '',
                    'firstname_err' => '',
                    'lastname_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => '',
                    'weight_err' => ''
                ];
                if(empty($dataFeedback['login_err']) && empty($dataFeedback['password_err']) && empty($dataFeedback['confirm_password_err']) && empty($dataFeedback['email_err']) && empty($dataFeedback['firstname_err']) && empty($dataFeedback['lastname_err']) && empty($dataFeedback['weight_err']))
                {
                    if($this->userModel->register($data['login'], $data['email'], $data['firstname'], $data['lastname'], $data['password'], $data['weight']))
                    {
                        $dataFeedback['message'] = 'Zosta??e?? poprawnie zarejestrowany. Przejd?? do strony logowania aby si?? zalogowa??';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wyst??pi?? b????d podczas tworzenia nowego u??ytkownika';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        die();
                    }
                }
            }
        }

        public function editUser()
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: PUT');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $data = [
                'password' => '',
                'password_confirm' => '',
                'weight' => ''
            ];

            $dataFeedback = [
                'message' => '',
                'password_err' => '',
                'password_confirm_err' => '',
                'weight_err' => ''
            ];
  
            if($_SERVER['REQUEST_METHOD'] == 'PUT' && isLogged())
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                    if(isset($dataJson->data->password))
                    {
                        $newPassword = trim(htmlspecialchars($dataJson->data->password));
                        if(isset($dataJson->data->password_confirm))
                        {
                            $newConfirmPassword = trim(htmlspecialchars($dataJson->data->password_confirm));
                            if(isset($dataJson->data->weight))
                            {
                                $newWeight = trim(htmlspecialchars($dataJson->data->weight));
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Poajwi?? si?? b????d. Odczyt wagi';
                                $dataFeedback['status'] = 'failed';
                                $dataJson = json_encode($dataFeedback);
                                echo $dataJson;
                                die();
                            }
                        }
                        else
                        {              
                            $dataFeedback['message'] = 'Pojawi?? si?? b????d. odczyt has??a';
                            $dataFeedback['status'] = 'failed';
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Pojawi?? si?? b????d. odczyt ponownego wprowadzenia has??a';
                        $dataFeedback['status'] = 'failed';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                
                
                // Init data
                $data = [
                    'password' => $newPassword,
                    'confirm_password' => $newConfirmPassword,
                    'weight' => $newWeight
                ];
                if(empty($data['weight']))
                {
                    if (empty($data['password']))
                    {
                        $dataFeedback['password_err'] = 'Prosz?? wprowad?? has??o';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                    if(strlen($data['password']) < 8)
                    {
                        $dataFeedback['password_err'] = 'Has??o musi zawiera?? co najmniej osiem znak??w';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
    
                    if (empty($data['confirm_password']))
                    {
                        $dataFeedback['confirm_password_err'] = 'Prosz?? wprowad?? has??o';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                    if(strcmp($data['password'], $data['confirm_password']))
                    {
                        $dataFeedback['confirm_password_err'] = 'Has??a si?? r????ni??';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                    if(empty($dataFeedback['password_err']) && empty($dataFeedback['confirm_password_err']))
                    {
    
                        if($this->userModel->updateUserPassword($_SESSION['user_id'], password_hash($data['password'], PASSWORD_BCRYPT)))
                        {
                            
                            infoLog(getenv('MODE'), 'User\'s data changed correctly');
                            //flash('register_success', 'User registered. Go to Login page', 'bg-success');
                            $dataFeedback['message'] = 'Dane u??ytkownika zosta??y poprawnie zmodyfikowane';
                            $dataFeedback['status'] = 'success';
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Pojawi?? si?? b????d';
                            $dataFeedback['status'] = 'failed';
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                            die();
                        }
                }
                else
                {
                    $dataFeedback['message'] = 'Pojawi?? si?? b????d';
                    $dataFeedback['status'] = 'failed';
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    die();
                    //$this->view('users/login', $dataFeedback);
                }
                }
                else
                {
                    if(!is_numeric((int)$data['weight']))
                    {
                        $dataFeedback['weight_err'] = 'Waga musi by?? liczb??';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }

                    if(empty($dataFeedback['weight_err']))
                    {
                        if($this->userModel->updateUserWeight($_SESSION['user_id'], $data['weight']))
                        {
                            $dataFeedback['message'] = 'Dane u??ytkownika zaktualizowane pomy??lnie';
                            $dataFeedback['status'] = 'success'; 
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Dane u??ytkownika nie zosta??y zmienione';
                            $dataFeedback['status'] = 'failed'; 
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                    }
                }
                

 
        }
        else
        {
            die('Aby kontynuowa?? musisz by?? zalogowany');
            //$this->view('users/login', $dataFeedback);
        }
    }

    public function deleteUser()
    {
        header('Access-Control-Allow-Origin: *');
         //header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
        $data = [
            'user_login' => ''
        ];

        $dataFeedback = [
            'message' => '',
            'status' => 'pending'
        ];


        if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
        {
            $dataJson = file_get_contents("php://input");
            $dataJson = json_decode($dataJson);
                
            
            // Init data
            $data = [
                'user_login' => $_SESSION['user_login']
            ];

            if($this->userModel->changeStatusUser($data['user_login'], 0))
            {
                    
                    infoLog(getenv('MODE'), 'User\'s account closed');
                    //flash('register_success', 'User registered. Go to Login page', 'bg-success');
                    $dataFeedback['message'] = 'Konto u??ytkownika zosta??o zablokowane. Prosz?? si?? wylogowa??.';
                    $dataFeedback['status'] = 'success';
                    $this->userModel->forceUpdateSQLDatabase();
                    http_response_code(200);
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    //$this->view('users/register', $dataErr);
                    die();
            }
            else
            {
                    $dataFeedback['message'] = 'Pojawi?? si?? b????d';
                    $dataFeedback['status'] = 'failed';
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    die();
            }
            $dataJson = json_encode($dataFeedback);
            echo $dataJson;
        }
        else
        {
            die('Aby kontynuowa?? musisz by?? zalogowany');
            //$this->view('users/login', $dataFeedback);
        }

}

        public function users($ID = 'all')
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $userArr = array();
            $userArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET')
                {
                    if($ID === 'all')
                    {
                        if($userArr['data'] = $this->userModel->getUsers())
                        {
                            $userArr['status'] = 'success';
                            echo json_encode($userArr);
                        }
                        else
                        {
                            $userArr['status'] = 'failed';
                            $userArr['data'] = ' U??ytkownik nie znaleziony';
                            echo json_encode($userArr);
                        }
                    }
                    else if(is_numeric($ID))
                    {
                        if($userArr['data'] = $this->userModel->getUserByID($ID))
                        {
                            $userArr['status'] = 'success';
                            echo json_encode($userArr);
                        }
                        else
                        {
                            $userArr['status'] = 'failed';
                            $userArr['data'] = ' U??ytkownik nie znaleziony';
                            echo json_encode($userArr);
                        }
                    }
                    else
                    {
                        $userArr['status'] = 'failed';
                        $userArr['data'] = ' Niepoprawne ID';
                        echo json_encode($userArr);
                    }

                }
            }
            else
            {
                $data = [
                    'login_err' => '',
                    'password_err' => ''
                ];
                $userArr['status'] = 'failed';
                $userArr['data']['message'] = 'Musisz si?? zalogowa??';
            }
        }


        public function login()
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $data = [
                'login' => '',
                'password' => '',
            ];

            $dataFeedback = [
                'message' => '',
                'login_err' => '',
                'password_err' => '',
            ];
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $dataJson = file_get_contents("php://input");
                $dataJson = json_decode($dataJson);
                if(isset($dataJson->data->login))
                {
                    $login = trim(htmlspecialchars($dataJson->data->login));
                    if(isset($dataJson->data->password) && !empty($dataJson->data->password))
                    {
                        $password = trim(htmlspecialchars($dataJson->data->password));
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Pojawi?? si?? b????d';
                        $dataFeedback['password_err'] = 'Prosz?? wprowad?? has??o';
                        $dataFeedback['type'] = 'failed';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Pojawi?? si?? b????d';
                    $dataFeedback['login_err'] = 'Prosz?? wprowad?? login u??ytkownika';
                    $dataFeedback['type'] = 'failed';
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    die();
                }
                
                
                // Init data
                $data = [
                    'login' => $login,
                    'password' => $password
                ];


                if(empty($dataFeedback['login_err'] && empty($dataFeedback['password_err'])))
                {
                    if($user = $this->userModel->login($data['login'], $data['password']))
                    {
                        $this->createUserSession($user['ID'], $user['loginU'], $user['firstname'], $user['lastname'], $user['weight']);
                        http_response_code(200);
                        $dataFeedback['message'] = 'Pomy??lnie zalogowano do systemu. Trwa przekierowanie do panelu.';
                        $dataFeedback['type'] = 'success';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Nieprawid??owy login lub has??o';
                        $dataFeedback['type'] = 'failed';
                        http_response_code(200);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                }
            }
            else
            {
    
            }
        }
        

        /**
         * Create session status of user
         *
         * @param [type] $id - ID of user
         * @param [type] $login - login user
         * @param [type] $is_active - status user
         * @param [type] $home_direcotry - home directory user
         * @param [type] $permission = user's permission
         * @return void
         */
        private function createUserSession($ID, $login, $firstname, $lastname, $weight)
        {
            $_SESSION['user_id'] = $ID;
            $_SESSION['user_login'] = $login;
            $_SESSION['user_firstname'] = $firstname;
            $_SESSION['user_lastname'] = $lastname;
            $_SESSION['user_weight'] = $weight;
        }

        /**
         * remove users data and destroy session
         *
         * @return void
         */
        private function destroyUserSession()
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_login']);
            unset($_SESSION['user_firstname']);
            unset($_SESSION['user_lastname']);
            unset($_SESSION['user_weight']);
            session_destroy();
        }

        public function logout()
        {
            $this->destroyUserSession();
        }
    }
?>