<?php
    namespace System\Controllers;

use System\MainController;


    class Newsletters extends MainController
    {
        
        public function __construct()
        {
            $this->newsletterModel = $this->model('Newsletter');
        }

        public function index($ID = 'all')
        {
            $this->newsletters($ID);
        }

        public function newsletters($ID = 'all')
        {
            header('Access-Control-Allow-Origin: *');
             //header('Content-Type: application/json');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $newsletterArr = array();
            $newsletterArr['status'] = 'pending';
            if(isLogged())
            {
                if($_SERVER['REQUEST_METHOD'] == 'GET' && isLogged())
                {
                    if($ID === 'all')
                    {
                        if($newsletterArr['data'] = $this->newsletterModel->fetchNewsletterEmails())
                        {
                            $newsletterArr['status'] = 'success';
                            echo json_encode($newsletterArr);
                        }
                        else
                        {
                            $newsletterArr['status'] = 'failed';
                            $newsletterArr['data'] = ' Użytkownik zapisani na newsletter nie zostali znalezieni';
                            echo json_encode($newsletterArr);
                        }
                    }
                    else if(is_numeric($ID))
                    {
                        if($newsletterArr['data'] = $this->newsletterModel->fetchNewsletterEmail($ID))
                        {
                            $newsletterArr['status'] = 'success';
                            echo json_encode($newsletterArr);
                        }
                        else
                        {
                            $newsletterArr['status'] = 'failed';
                            $newsletterArr['data'] = ' Użytkownik nie znaleziony na liście newslettera';
                            echo json_encode($newsletterArr);
                        }
                    }
                    else
                    {
                        $newsletterArr['status'] = 'failed';
                        $newsletterArr['data'] = ' Niepoprawne ID';
                        echo json_encode($newsletterArr);
                    }

                }
            }
            else
            {
                $newsletterArr['status'] = 'failed';
                $newsletterArr['data']['message'] = 'Musisz się zalogować';
                echo json_encode($newsletterArr);
                die();
            }
        }

        public function add()
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: POST');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
           $data = [
               'is_agree' => ''
           ];

           $dataFeedback = [
               'message' => '',
               'is_agree_err' => ''
           ];
           if($_SERVER['REQUEST_METHOD'] == 'POST' && isLogged())
           {
               $dataJson = file_get_contents("php://input");
               $dataJson = json_decode($dataJson);
               if(isset($dataJson->data->is_agree) && !empty($dataJson->data->is_agree) && is_numeric($dataJson->data->is_agree) && $dataJson->data->is_agree == 1)  
                {
                    $is_agree = $dataJson->data->is_agree;
                }
               else
               {
                   $dataFeedback['message'] = 'Nieprawidłowa wartość';
                   $dataFeedback['is_agree_err'] = 'To musi być wartość 1 jeśli użytkownik się zgadza';
                   echo json_encode($dataFeedback);
                   die();
               }
               
               $data = [
                   'is_agree' => $is_agree
               ];

               if($this->newsletterModel->checkIfExists($_SESSION['user_id']))
               {
                $dataFeedback['message'] = 'Użytkownik już jest zapisany na newsletter';
                echo json_encode($dataFeedback);
                die();
               }

               if(empty($dataFeedback['is_agree_err']))
               {
                   if($this->newsletterModel->create($_SESSION['user_id']))
                   {
                       $dataFeedback['message'] = 'Zostałeś poprawnie zarejestrowany na newsletter.';
                       http_response_code(201);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas zapisu na newsletter';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       die();
                   }
               }
           }
        }
        
        public function delete()
        {
            header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
           header('Access-Control-Allow-Methods: DELETE');
           header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

           if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isLogged())
           {
               $dataJson = file_get_contents("php://input");
               $dataJson = json_decode($dataJson);
               

               if(!$this->newsletterModel->checkIfExists($_SESSION['user_id']))
               {
                $dataFeedback['message'] = 'Użytkownik nie jest zapisany na newsletter';
                $dataFeedback['status'] = 'failed';
                echo json_encode($dataFeedback);
                die();
               }

               if(empty($dataFeedback['is_agree_err']))
               {
                   if($this->newsletterModel->delete($_SESSION['user_id']))
                   {
                       $dataFeedback['message'] = 'Zostałeś poprawnie usunięty z newslettera.';
                       $dataFeedback['status'] = 'success';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       echo $dataJson;
                   }
                   else
                   {
                       $dataFeedback['message'] = 'Wystąpił błąd podczas rezygnacji z newslettera';
                       $dataFeedback['status'] = 'failed';
                       http_response_code(200);
                       $dataJson = json_encode($dataFeedback);
                       die();
                   }
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