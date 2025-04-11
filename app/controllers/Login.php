<?php

class Login extends Controller
{

    public function index()
    {
        $data = []; $model = new Model;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['nic'])) {

                $id = $_POST['nic'];
<<<<<<< HEAD
                $_SESSION['userid'] = $id;
=======
>>>>>>> 6a4db595ba97f516aeddf756ddbbbf8247590b55

                // Check if 'd' exists in the string
                if (strpos($id, 'd') !== false) {
                    $_SESSION['user_type'] = "doctor";
                    $user = new Doctor;
                } elseif (strpos($id, 'p') !== false) {
                    $user = new Patient;
                    $_SESSION['user_type'] = "patient";
                } elseif (strpos($id, 'h') !== false) {
<<<<<<< HEAD
                    $user = new PharmacyModel;
                    $_SESSION['user_type'] = "pharmacy";
                } elseif (strpos($id, 'l') !== false) {
                    $user = new LabModel;
=======
                    $user = new Pharmacy;
                    $_SESSION['user_type'] = "pharmacy";
                } elseif (strpos($id, 'l') !== false) {
                    $user = new Lab;
>>>>>>> 6a4db595ba97f516aeddf756ddbbbf8247590b55
                    $_SESSION['user_type'] = "lab";
                } elseif (strpos($id, 'a') !== false) {
                    $user = new Admin;
                    $_SESSION['user_type'] = "admin";
                }


                //password_verify($_POST['password'], $row->password
                $arr['nic'] = $_POST['nic'];
                $row = $user->first($arr);

                if ($row) {
<<<<<<< HEAD
                    if (password_verify($_POST['password'], $row->password)) {
                        $_SESSION['USER'] = $row; // Save user details in the session
                        $model->loggedin();
=======
                    if (($_POST['password']== $row->password)) {
                        $_SESSION['USER'] = $row; // Save user details in the session
                        $model->loggedin();
                        $_SESSION['userid'] = $row->id;
>>>>>>> 6a4db595ba97f516aeddf756ddbbbf8247590b55
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