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
            
            if(isset($_GET['selected_date'])){
                $date = $_GET['selected_date'];
            }else{
                $date = date('Y-m-d');
            }

            $appointment = new Appointments();
            $appointmentsOnDate = $appointment->getTodayAppointments($date);
            $data['appointmentsOnDate'] = $appointmentsOnDate;
            $data['date'] = $date;
            //print_r($data);
            //echo $appointmentsOnDate[0]->appointment_id;

            $this->view('Doctor/appointments','appointments',$data);
        }

        public function medication_Details($id, $app_id){

            //print_r($id);
            $data['patient_id'] = $id;
            $data['app_id'] = $app_id;

            $this->view('Doctor/medication_Details','today-checkups',$data);
        }

        public function patient_details($appointment_id){

            $_SESSION['appointment_id'] = $appointment_id;

            $appointments = new Appointments;

            $patient_details = $appointments->getPatientDetails($appointment_id);
            $this->view('Doctor/patient_details','today-checkups',$patient_details);
            //echo $_SESSION['appointment_id'];
        }

        public function logout(){
            $this->view('Doctor/logout','logout' );
        }

        public function display_record($patient_id){
            //echo $patient_id;

            $medicalRecord = new MedicalRecord();
            $pastRecords = $medicalRecord->getPastRecords($patient_id);

            print_r($pastRecords);

            $this->view('Doctor/display_record','today-checkups' );
        }

        public function medical_record($patient_id='', $app_id=''){

            $medicalRecord = new MedicalRecord();
            //$pastRecords = $medicalRecord->getPastRecords($patient_id);

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

        public function handleFetchRequest($data) {

                if (isset($data['date'])) {
                    $scheduledDate = $data['date'];
                    $formattedDate = (new DateTime($scheduledDate))->format('Y-m-d');

                    $appointments = new Appointments();
                    $appointmentsList = $appointments->getTodayAppointments($formattedDate);

                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'success',
                        'date' => $formattedDate,
                        'appointments' => $appointmentsList
                    ]);
                    exit;
                } else {
                    // Handle missing date error
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Date not provided']);
                    exit;
                }
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $input = file_get_contents("php://input");

        $data = json_decode($input, true);
    
        if (isset($data['date'])) {
            $doctor = new Doctor();
            $doctor->handleFetchRequest($data);
        }
    }
?>