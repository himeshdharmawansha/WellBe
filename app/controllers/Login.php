<?php

class Login extends Controller
{

    public function index()
    {
        $data = []; $model = new Model;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['nic'])) {

                $id = $_POST['nic'];
                $_SESSION['userid'] = $id;

                

                // Check if 'd' exists in the string
                if ($_POST['userType'] == 'Doctor') {
                    $_SESSION['user_type'] = "doctor";
                    $user = new Doctor;
                    $arr['nic'] = $_POST['nic'] . "d" ;
                } elseif ($_POST['userType'] == 'Patient') {
                    $user = new Patient;
                    $_SESSION['user_type'] = "patient";
                    $arr['nic'] = $_POST['nic'] . "p" ;
                } elseif ($_POST['userType'] == 'Pharmacist') {
                    $user = new PharmacyModel;
                    $_SESSION['user_type'] = "pharmacy";
                    $arr['nic'] = $_POST['nic'] . "h" ;
                } elseif ($_POST['userType'] == 'LabTech') {
                    $user = new LabModel;
                    $_SESSION['user_type'] = "lab";
                    $arr['nic'] = $_POST['nic'] . "l" ;
                } elseif ($_POST['userType'] == 'Admin') {
                    $user = new Admin;
                    $_SESSION['user_type'] = "admin";
                    $arr['nic'] = $_POST['nic'] . "a" ;
                }elseif ($_POST['userType'] == 'Receptionist') {
                    $user = new Receptionist;
                    $_SESSION['user_type'] = "receptionist";
                    $arr['nic'] = $_POST['nic'] . "r" ;
                }

                //password_verify($_POST['password'], $row->password
                $row = $user->first($arr);

                if ($row) {
                    if ($_POST['password'] == $row->password) {
                
                        $_SESSION['USER'] = $row; // Save user details in the session
                        $model->loggedin();
                        redirect($_SESSION['user_type']);
                    } else {
                        $user->errors['password'] = 'Wrong password'; // Add specific error for wrong password
                    }
                } else {
                    $user->errors['nic'] = 'NIC not found';
                }

                $data['errors'] = $user->errors;
            }
        }

        $this->view('login', '', $data);
    }
}