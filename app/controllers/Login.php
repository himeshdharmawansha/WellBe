<?php

class Login extends Controller
{

    public function index()
    {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['nic'])) {

                $id = htmlspecialchars(trim($_POST['nic']));
                $password = htmlspecialchars(trim($_POST['password']));

                // Determine user type based on the NIC identifier
                if (strpos($id, 'd') !== false) {
                    $_SESSION['user_type'] = "doctor";
                    $user = new Doctor;
                } elseif (strpos($id, 'p') !== false) {
                    $_SESSION['user_type'] = "patient";
                    $user = new Patient;
                } elseif (strpos($id, 'h') !== false) {
                    $_SESSION['user_type'] = "pharmacy";
                    $user = new Pharmacy;
                } elseif (strpos($id, 'l') !== false) {
                    $_SESSION['user_type'] = "lab";
                    $user = new Lab;
                } elseif (strpos($id, 'a') !== false) {
                    $_SESSION['user_type'] = "admin";
                    $user = new Admin;
                }

                $arr['nic'] = $id;
                $row = $user->first($arr);

                if ($row) {
                    // Compare plain-text passwords
                    if ($password === $row->password) {
                        $_SESSION['USER'] = $row; // Save user details in the session
                       // $user->loggedin();
                        $_SESSION['userid'] = $row->id;
                        redirect($_SESSION['user_type']);
                    } else {
                        $user->errors['password'] = 'Wrong password';
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
