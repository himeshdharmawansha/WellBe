<?php

class Receptionist extends Controller
{

   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'todayAppointments' => ["fas fa-calendar-alt", "Appointments"],
         'chat' => ["fas fa-comment-dots", "Chat"],
         'logout' => ["fas fa-sign-out-alt", "Logout"]
      ],
      'userType' => 'receptionist'
   ];

   public function __construct()
   {
      if(!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "receptionist"){
         redirect('login');
         exit;
      }
   }

   public function index()
   {
      $this->view('Receptionist/dashboard', 'dashboard');
   }

   public function todayAppointments()
   {
      $timeslot = new Timeslot(); 
      $data['today_sessions'] = $timeslot->getTodaySessions(); // Fetch all today appointments
      //print_r($data['today_sessions']);
      
      $this->view('Receptionist/todayAppointments', 'Appointments', $data);
   }

   public function appointmentQueue()
   {
      $slot_id = $_GET['slot_id'] ?? null;
      $doctor_id = $_GET['doctor_id'] ?? null;

      $appointmentModel = new Appointments();

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
         $slot_id = $_POST['slot_id'] ?? null;
         $doctor_id = $_POST['doctor_id'] ?? null;

         if (!empty($_POST['appointments'])) {
            foreach ($_POST['appointments'] as $app) {
               $id = $app['appointment_id'];
               $newPatientStatus = $app['patient_status'];
               $newPaymentStatus = $app['payment_status'];
               $originalPatientStatus = $app['original_patient_status'];
               $originalPaymentStatus = $app['original_payment_status'];
      
               // Only update if either status has changed
               if ($newPatientStatus !== $originalPatientStatus || $newPaymentStatus !== $originalPaymentStatus) {
                  error_log("Updating appointment ID: $id | Patient: $newPatientStatus | Payment: $newPaymentStatus");
                  $appointmentModel->updateStatus($id, $newPatientStatus, $newPaymentStatus, $slot_id, $doctor_id);
               }
            }
         }
         // Redirect back after update
         header("Location: " . ROOT . "/Receptionist/todayAppointments"); 
         exit();
      }
      
      if ($slot_id && $doctor_id) {
         $appointments = new Appointments();
         $data['appointments'] = $appointments->getAppointmentsForSession($slot_id, $doctor_id);
      }
      $this->view('Receptionist/appointmentQueue', 'Appointments', $data);
      
   }

   public function appointmentsUpcoming()
   {
      $this->view('Receptionist/appointmentsUpcoming', 'appointmentsUpcoming');
   }

   public function appointmentsPast()
   {
      $this->view('Receptionist/appointmentsPast', 'appointmentsPast');
   }

   public function patients()
   {
      $this->view('Admin/patients', 'patients');
   }

   
   public function chat()
   {
      $this->view('Receptionist/chat', 'chat');
   }
   
   public function logout()
   {
      $this->view('Receptionist/logout', 'logout');
   }

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }
}
