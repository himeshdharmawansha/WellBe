<?php

class Signup extends Controller
{
    public function index()
    {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // Save the data from the first form to the session

            $_SESSION['newNIC'] = $_POST['nic'] . "p";
            $_SESSION['form1_data'] = $_POST;

            $user = new Patient;

            if ($user->validate_first_form($_SESSION['form1_data'])) {
                redirect("signup/form2");
            }
            
            $data['errors'] = $user->errors;

        }

        $this->view('patientForm1', "",$data);
    }

    public function form2()
    {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            
            $fullData = array_merge($_SESSION['form1_data'] ?? [], $_POST);
            //echo $_SESSION['newNIC'];
            $fullData['nic'] = $_SESSION['newNIC'];
            echo $fullData['nic'];
            $_SESSION['form2_data'] = $_POST;

            //hash password
            $hashedPassword = password_hash($fullData['password'], PASSWORD_DEFAULT);
            $fullData['password'] = $hashedPassword;


            unset($_SESSION['form1_data']);

            $user = new Patient;

            if ($user->validate_second_form($_SESSION['form2_data'])) {
                $user->insert($fullData);
                redirect("login");
            }
            $data['errors'] = $user->errors;
        }

        $this->view('patientForm2', $data);
    }
}
