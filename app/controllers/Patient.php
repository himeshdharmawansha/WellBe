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
      $this->view('Patient/doc_appointment', 'search_for_doctor');
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

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }
}
