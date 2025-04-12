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
      $appointmentsModel = new Appointments();
      $patient_id = $_SESSION['USER']->id;
   
      $appointments = $appointmentsModel->getAllAppointmentsForPatient($patient_id);
   
      // Pass appointment data to the dashboard view
      $this->view('Patient/patient_dashboard', 'patient_dashboard', ['appointments' => $appointments]);
   }
   

   public function medicalreports()
   {
      $this->view('Patient/medicalreports', 'medicalreports');
   }
   public function labreports()
   {
      $this->view('Patient/labreports', 'labreports');
   }

   public function refund()
   {
      $payment = new Payment();
      $payment->refund();

      $this->view('Patient/patient_dashboard', 'patient_dashboard');
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

            $name = explode(" ", $doctor_name);

            $first_name = $name[0];
            $last_name = isset($name[1]) ? $name[1] : "";

            $docId = $doctor->getDoctorId($first_name, $last_name);
            $data['docId'] = $docId[0]->id;
            $data['doctorFee'] = $doctor->getFeesByDoctorId($docId[0]->id)[0]->fees;
            //echo $docId[0]->id;

            $timeslot = new Timeslot();

            //get available dates of a doctor
            $availableDates = $timeslot->getAvailableDays($docId[0]->id);

            $appointment = new Appointments();

            //get current appointment num
            $appointmentNums = $appointment->getAppointment($docId[0]->id, $availableDates['todayId']);

            foreach ($availableDates['matchedDates'] as &$day) {
               $isScheduled = NULL;
               foreach ($appointmentNums as $appointmentNum) {
                  if ($day['slot_id'] === $appointmentNum->date) {
                     $isScheduled = $appointmentNum->appointment_id + 1;
                  }
               }
               if (!$isScheduled) {
                  $isScheduled = 1;
               }
               $day['appointment_id'] = $isScheduled;
            }

            $data['dates'] = $availableDates['matchedDates'];
         }
         //$this->view('Patient/doc_appointment', 'doc_appointment', $data);
      }

      $data['doctors'] = $doctor->getDoctorsWithSpecializations(); // Fetch all doctor name

      //print_r($data['doctors']);


      $this->view('Patient/doc_appointment', 'doc_appointment', $data);
   }

   public function appointments()
   {
       $appointmentsModel = new Appointments();
       $patient_id = $_SESSION['USER']->id;
   
       $appointments = $appointmentsModel->getAllAppointmentsForPatient($patient_id);
   
       // Fix: pass active tab correctly
       $this->view('Patient/appointments', 'appointments', ['appointments' => $appointments]);
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
      $payment = new Payment();
      $amount = $payment->getPatientWalletAmount();
      $data['walletAmount'] = $amount;

      $this->view('Patient/hello', 'hello', $data);
   }

   public function getAppointmentdata()
   {

      $input = file_get_contents("php://input");
      $data = json_decode($input, true);

      $name = explode(" ", $data['doctor']);

      $first_name = $name[0];
      $last_name = isset($name[1]) ? $name[1] : "";
      $doctor = new Doctor();

      //get doctor id from name
      $docId = $doctor->getDoctorId($first_name, $last_name);
      $data['docId'] = $docId[0]->id;
      //patient id
      $data['patientId'] = $_SESSION['USER']->id;

      //get date id by date
      $timeslot = new Timeslot();
      $dateId = $timeslot->getDateId($data['appointment_date']);
      $data['dateId'] = $dateId[0]->slot_id;

      //find patient type(returning or new)
      $medicalRecord = new MedicalRecord();
      $pastRecords = $medicalRecord->getPatientType();
      if (!empty($pastRecords) && count($pastRecords) > 0) {
         $data['patient_type'] = "RETURNING";
      } else {
         $data['patient_type'] = "NEW";
      }

      $appointment = new Appointments();

      //Deduct from wallet if payment is made via E-Wallet
      if ($data['payment_method'] == "wallet") {
         $appointment->decWalletAmount();
      }


      //Deduct from wallet if payment is made via E-Wallet
      if ($data['payment_method'] == "wallet") {
         $appointment->decWalletAmount();
      }


      if ($data && $appointment->checkAppointmentExists($data)) {
         $response = [
            "success" => true,
            "message" => "Appointment already exists for this date and time"
         ];
      } elseif ($data && $appointment->makeNewAppointment($data)) {
         $response = [
            "success" => true,
            "message" => "Appointment created successfully"
         ];
      } else {
         $response = [
            "success" => false,
            "message" => "Invalid data or failed to create appointment"
         ];
      }

      // Return JSON response
      header('Content-Type: application/json');
      echo json_encode($response);
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
         'patient_id' => $_SESSION['USER']->id,
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
