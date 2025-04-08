<?php

class Patient extends Controller
{

   private $data = [
      'elements' => [
         'patient_dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'medicalreports' => ["fas fa-notes-medical", "View Medical Reports"],
         'labreports' => ["fas fa-flask", "View Lab Reports"],
         'doc_appointment' => ["fas fa-user-md", "Search for a Doctor"],
         'appointments' => ["fas fa-calendar-alt", "Appointments"],
         'chat' => ["fas fa-comments", "Chat with the Doctor"],
         'logout' => ["fas fa-sign-out-alt", "Logout"]
      ],
      'userType' => 'patient'
   ];

   public function __construct()
   {
      if (!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "patient") {
         redirect('login');
         exit;
      }
   }

   public function index()
   {
      $this->view('Patient/patient_dashboard', 'patient_dashboard');
   }

   public function medicalreports()
   {
      $this->view('Patient/medicalreports', 'medicalreports');
   }
   public function labreports()
   {
      $this->view('Patient/labreports', 'labreports');
   }
   public function doc_appointment()
   {
      $data = [];
      $doctor = new Doctor(); // Instantiate the Doctor model

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         
         $doctor_name = isset($_POST['doctor']) ? $_POST['doctor'] : '';
         $specialization = isset($_POST['specialization']) ? $_POST['specialization'] : '';
 
         $data['doctor_name'] = $doctor_name;

         if (empty($doctor_name) || empty($specialization)) {
             $data['error'] = "Please select a doctor.";
         } else {

            $name = explode(" ",$doctor_name);

            $first_name = $name[0];
            $last_name = isset($name[1]) ? $name[1] : "";

            $docId = $doctor->getDoctorId($first_name,$last_name);
            $data['docId'] = $docId[0]->id;
            $data['doctorFee'] = $doctor->getFeesByDoctorId($docId[0]->id)[0]->fees;
            //echo $docId[0]->id;

            $timeslot = new Timeslot();

            //get available dates of a doctor
            $availableDates = $timeslot->getAvailableDays($docId[0]->id);

            $appointment = new Appointments();

            //get current appointment num
            $appointmentNums = $appointment->getAppointment($docId[0]->id,$availableDates['todayId']);

            foreach($availableDates['matchedDates'] as &$day){
               $isScheduled = NULL;
               foreach($appointmentNums as $appointmentNum){
                  if($day['slot_id'] === $appointmentNum->date){
                     $isScheduled = $appointmentNum->appointment_id+1;
                  }
               }
               if(!$isScheduled){
                  $isScheduled = 1;
               }
               $day['appointment_id'] = $isScheduled;
            }

            $data['dates'] = $availableDates['matchedDates'];
         }
         //$this->view('Patient/doc_appointment', 'doc_appointment', $data);
      }

      $data['doctors'] = $doctor->getDoctorsWithSpecializations(); // Fetch all doctor name
    

      $this->view('Patient/doc_appointment', 'doc_appointment', $data);

   }

   public function appointments()
   {
      $this->view('Patient/appointments', 'appointments');
   }
   public function chat()
   {
      $this->view('Patient/chat', 'chat');
   }
   public function logout()
   {
      $this->view('Patient/logout', 'logout');
   }

   public function edit_profile()
   {
      $this->view('Patient/edit_profile', 'edit_profile');
   }
   public function medical_rec()
   {
      $this->view('Patient/medical_rec', 'medical_rec');
   }

   public function Lab_download()
   {
      $this->view('Patient/Lab_download', 'Lab_download');
   }

public function hello()
{
    $this->view('Patient/hello', 'hello');
}

   public function generatehash()
   {
      $doc_id = $_POST['doc_id'];
      $doctorModel = new Doctor();
      $amount = $doctorModel->getFeesByDoctorId($doc_id)[0]->fees;
      $merchant_id = '1228628';
      $order_id = $_POST['order_id'];
      $currency = 'LKR';
      $merchant_secret = 'MzkxMDUxMDYzNzIxMTExNDMyOTMyMDQ1NTQ0ODU3MzM1MTk3MDU4NA==';
      $hash = strtoupper(
         md5(
             $merchant_id . 
             $order_id . 
             number_format($amount, 2, '.', '') . 
             $currency .  
             strtoupper(md5($merchant_secret)) 
         ) 
     );

     $data = [
         'hash' => $hash,
         'order_id' => $order_id,
         'items' => 'Appointment Fees',
         'amount' => $amount,
         'currency' => $currency,
         'merchant_id' => $merchant_id,
         'return_url' => 'http://localhost/medicare/patient/hello',
         'cancel_url' => 'http://localhost/medicare/patient/hello',
         'notify_url' => 'https://well-be.loca.lt/wellbe/patient/getPaymentData',
         'first_name' => 'John',
         'last_name' => 'Doe',
         'email' => 'ammu@gmail.com',
         'phone' => '0771234567',
         'address' => 'No.1, Galle Road',
         'city' => 'Colombo',
         'country' => 'Sri Lanka',

     ];

      header('Content-Type: application/json');
      echo json_encode($data);
   }

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }
}
