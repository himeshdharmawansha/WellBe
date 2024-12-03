<?php

    class Doctor extends Controller{

        private $data = [
            'elements' => [
                'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
                'appointments' => ["fas fa-calendar-alt", "Appointments"],
                'today_checkups' => ["fas fa-user", "Today-checkups"],
                'chat' => ["fa-solid fa-comment-dots", "Chat"],
                'logout' => ["fas fa-sign-out-alt", "Logout"]
            ],
            'userType' => 'doctor'
        ];

        public function __construct()
        {
            if(!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "doctor"){
                redirect('login');
                exit;
            }
        }

        public function index(){

            $this->view('Doctor/dashboard','dashboard');
        }


        public function today_checkups(){

            $appointments = new Appointments;
            
            $today_appointments = $appointments->getTodayAppointments();

            $this->view('Doctor/today-checkups','today-checkups',$today_appointments);
        }

        public function appointments(){
            $this->view('Doctor/appointments','appointments');
        }

        public function medication_Details($id, $app_id){

            //print_r($id);
            $data['id'] = $id;
            echo $id;
            echo $app_id;
            $data['app_id'] = $app_id;
            //echo $data[1];
            //$first_name = urldecode($_GET['first_name']);

            $this->view('Doctor/medication_Details','today-checkups',$data);
        }

        public function patient_details($appointment_id){

            $_SESSION['appointment_id'] = $appointment_id;

            $appointments = new Appointments;


            $patient_details = $appointments->getPatientDetails($appointment_id);
            $this->view('Doctor/patient_details','today-checkups',$patient_details);
            echo $_SESSION['appointment_id'];
        }

        public function logout(){
            $this->view('Doctor/logout','logout' );
        }

        public function display_record(){
            $this->view('Doctor/display_record','today-checkups' );
        }

        public function medical_record(){
            $this->view('Doctor/medical_record','today-checkups' );
        }

        public function Lab_download(){
            $this->view('Doctor/Lab_download','today-checkups' );
        }

        public function chat()
        {
            $this->view('Doctor/chat', 'chat');
        }

        public function renderComponent($component,$active)
        {
            $elements = $this->data['elements'];
            $userType = $this->data['userType'];

            $filename = "../app/views/Components/{$component}.php";
            require $filename;
        }

        public function renderCalender($component)
        {

            $timeslot = new Timeslot;
            $timeslot -> createTimeslot();
            $_SESSION['schedule'] = $timeslot -> getSchedule();
            $_SESSION['schedule'];

            $filename = "../app/views/Components/{$component}.php";
            require $filename;
        }

        public function renderChart($component)
        {
            $filename = "../app/views/Components/Doctor/{$component}.php";
            require $filename;
        }
    }