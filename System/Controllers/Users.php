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
                                            $dataFeedback['message'] = 'Proszę wprowadź wagę.';
                                            $dataFeedback['weight_err'] = 'Waga musi być większa od zera';
                                            echo json_encode($dataFeedback);
                                            die('');
                                        }
                                    }
                                    else
                                    {
                                        $dataFeedback['message'] = 'Wprowadź nazwisko';
                                        $dataFeedback['lastname_err'] = 'Pole nazwisko jest puste';
                                        echo json_encode($dataFeedback);
                                        die('');
                                    }
                                }
                                else
                                {
                                    $dataFeedback['message'] = 'Wprowadź imię';
                                    $dataFeedback['firstname_err'] = 'Pole imię jest puste';
                                    echo json_encode($dataFeedback);
                                    die('');
                                }
                            }
                            else
                            {
                                $dataFeedback['message'] = 'Wprowadź adres email';
                                $dataFeedback['email_err'] = 'Pole na adres email jest puste';
                                echo json_encode($dataFeedback);
                                die();
                            }
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Proszę wprowadź hasło jeszcze raz';
                            $dataFeedback['password_confirm_err'] = 'Hasło powinno składać się z conajmniej ośmiu znaków';
                            echo json_encode($dataFeedback);
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wprowadź hasło';
                        $dataFeedback['password_err'] = 'Pole hasło jest puste. Hasło musi się składać z conajmniej ośmiu znaków';
                        echo json_encode($dataFeedback);
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Proszę wprowadź nazwę użytkownika';
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
                        $dataFeedback['login_err'] = 'Login jest użyty przez innego użytkownika';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }

                    if($this->userModel->findUserByEmail($data['email']))
                    {
                        $dataFeedback['email_err'] = 'Email jest użyty przez innego użytkownika';
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
                    $dataFeedback['password_err'] = 'Proszę wprowadź hasło';
                    $dataJson = json_encode($dataFeedback);
                    echo $dataJson;
                    die();
                }

                if(strcmp($data['password'], $data['confirm_password']))
                {
                    $dataFeedback['confirm_password_err'] = 'Hasła się różnią';
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
                        $dataFeedback['message'] = 'Zostałeś poprawnie zarejestrowany. Przejdź do strony logowania aby się zalogować';
                        http_response_code(201);
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Wystąpił błąd podczas tworzenia nowego użytkownika';
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
                                $dataFeedback['message'] = 'Poajwił się błąd. Odczyt wagi';
                                $dataFeedback['status'] = 'failed';
                                $dataJson = json_encode($dataFeedback);
                                echo $dataJson;
                                die();
                            }
                        }
                        else
                        {              
                            $dataFeedback['message'] = 'Pojawił się błąd. odczyt hasła';
                            $dataFeedback['status'] = 'failed';
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                            die();
                        }
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Pojawił się błąd. odczyt ponownego wprowadzenia hasła';
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
                        $dataFeedback['password_err'] = 'Proszę wprowadź hasło';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                    if(strlen($data['password']) < 8)
                    {
                        $dataFeedback['password_err'] = 'Hasło musi zawierać co najmniej osiem znaków';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
    
                    if (empty($data['confirm_password']))
                    {
                        $dataFeedback['confirm_password_err'] = 'Proszę wprowadź hasło';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                    if(strcmp($data['password'], $data['confirm_password']))
                    {
                        $dataFeedback['confirm_password_err'] = 'Hasła się różnią';
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
                            $dataFeedback['message'] = 'Dane użytkownika zostały poprawnie zmodyfikowane';
                            $dataFeedback['status'] = 'success';
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Pojawił się błąd';
                            $dataFeedback['status'] = 'failed';
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                            die();
                        }
                }
                else
                {
                    $dataFeedback['message'] = 'Pojawił się błąd';
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
                        $dataFeedback['weight_err'] = 'Waga musi być liczbą';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }

                    if(empty($dataFeedback['weight_err']))
                    {
                        if($this->userModel->updateUserWeight($_SESSION['user_id'], $data['weight']))
                        {
                            $dataFeedback['message'] = 'Dane użytkownika zaktualizowane pomyślnie';
                            $dataFeedback['status'] = 'success'; 
                            http_response_code(200);
                            $dataJson = json_encode($dataFeedback);
                            echo $dataJson;
                        }
                        else
                        {
                            $dataFeedback['message'] = 'Dane użytkownika nie zostały zmienione';
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
            die('Aby kontynuować musisz być zalogowany');
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
                    $dataFeedback['message'] = 'Konto użytkownika zostało zablokowane. Proszę się wylogować.';
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
                    $dataFeedback['message'] = 'Pojawił się błąd';
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
            die('Aby kontynuować musisz być zalogowany');
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
                            $userArr['data'] = ' Użytkownik nie znaleziony';
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
                            $userArr['data'] = ' Użytkownik nie znaleziony';
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
                $userArr['data']['message'] = 'Musisz się zalogować';
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
                        $dataFeedback['message'] = 'Pojawił się błąd';
                        $dataFeedback['password_err'] = 'Proszę wprowadź hasło';
                        $dataFeedback['type'] = 'failed';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                        die();
                    }
                }
                else
                {
                    $dataFeedback['message'] = 'Pojawił się błąd';
                    $dataFeedback['login_err'] = 'Proszę wprowadź login użytkownika';
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
                        $dataFeedback['message'] = 'Pomyślnie zalogowano do systemu. Trwa przekierowanie do panelu.';
                        $dataFeedback['type'] = 'success';
                        $dataJson = json_encode($dataFeedback);
                        echo $dataJson;
                    }
                    else
                    {
                        $dataFeedback['message'] = 'Nieprawidłowy login lub hasło';
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