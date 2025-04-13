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
                if (strpos($id, 'd') !== false) {
                    $_SESSION['user_type'] = "doctor";
                    $user = new Doctor;
                } elseif (strpos($id, 'p') !== false) {
                    $user = new Patient;
                    $_SESSION['user_type'] = "patient";
                } elseif (strpos($id, 'h') !== false) {
                    $user = new PharmacyModel;
                    $_SESSION['user_type'] = "pharmacy";
                } elseif (strpos($id, 'l') !== false) {
                    $user = new LabModel;
                    $_SESSION['user_type'] = "lab";
                } elseif (strpos($id, 'a') !== false) {
                    $user = new Admin;
                    $_SESSION['user_type'] = "admin";
                }

                //password_verify($_POST['password'], $row->password
                $arr['nic'] = $_POST['nic'];
                $row = $user->first($arr);

                if ($row) {
                    if (password_verify($_POST['password'], $row->password)) {
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
