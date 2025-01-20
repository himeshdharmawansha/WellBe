<?php

class Login extends Controller
{

    public function index()
    {
        $data = []; $model = new Model;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['nic'])){

                $id = $_POST['nic'];
            
                // Check if 'd' exists in the string
                if (strpos($id, 'd') !== false) {
                    $_SESSION['user_type'] = "doctor";
                    $user = new Doctor;
                }
                elseif (strpos($id, 'p') !== false) {
                    $user = new Patient;
                    $_SESSION['user_type'] = "patient";
                }
                elseif (strpos($id, 'h') !== false) {
                    $user = new Pharmacy;
                    $_SESSION['user_type'] = "pharmacy";
                }
                elseif (strpos($id, 'l') !== false) {
                    $user = new Lab;
                    $_SESSION['user_type'] = "lab";
                }
                elseif (strpos($id, 'a') !== false) {
                    $user = new Admin;
                    $_SESSION['user_type'] = "admin";
                }else{
                    $_SESSION['user_type'] = "undifined";
                }

            
                //password_verify($_POST['password'], $row->password
                if($_SESSION['user_type'] != "undifined"){
                    $arr['nic'] = $_POST['nic'];
                    $row = $user->first($arr);


                    if ($row) {
                        if ($_POST['password'] === $row->password) {
                            $_SESSION['USER'] = $row; // Save user details in the session
                            //session_start();
                          //password_verify($_POST['password'], $row->password)
                            $_SESSION['userid'] = $row->id;
                            redirect($_SESSION['user_type']);
                        } else {
                            $user->errors['password'] = 'Wrong password'; // Add specific error for wrong password
                        }


                    } else {
                        $user->errors['nic'] = 'NIC not found';
                    }
    
                    $data['errors'] = $user->errors;
                }else{
                    $data['errors'] = ['nic' => 'NIC not found'];
                }
            }

        }
        
        $this->view('login','', $data);
    }
}
