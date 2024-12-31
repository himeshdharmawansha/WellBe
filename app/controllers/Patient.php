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
            if(!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "patient"){
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
   
       if ($_SERVER['REQUEST_METHOD'] == "POST") {
           if (isset($_POST['doctor']) && isset($_POST['specialization'])) {
               $doctorName = htmlspecialchars(trim($_POST['doctor']));
               $specialization = htmlspecialchars(trim($_POST['specialization']));
   
               $doctorModel = new Doctor;
               $timeslotModel = new Timeslot;
   
               // Fetch the selected doctor's information
               $doctor = $doctorModel->findDoctorByNameAndSpecialization($doctorName, $specialization);
   
               if ($doctor) {
                   // Fetch available time slots for the doctor
                   $data['timeslots'] = $timeslotModel->getappDate($doctor->id);
                   $data['selectedDoctor'] = $doctor;
               } else {
                   $data['errors']['doctor'] = "No doctor found with the given name and specialization.";
               }
           }
       }
   
       // Fetch all doctors and their specializations to populate the dropdowns
       $doctorModel = new Doctor;
       $data['doctors'] = $doctorModel->getAllDoctors();
       $data['specializations'] = $doctorModel->getAllSpecializations();
   
       $this->view('Patient/doc_appointment', 'doc_appointment', $data);
   }

   public function get_specializations_by_doctor()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $doctorName = $_POST['doctor'] ?? null;
     
         if ($doctorName) {
             $doctorModel = new Doctor();
             $specializations = $doctorModel->getSpecializationsByDoctor($doctorName);
     
             header('Content-Type: application/json');
             echo json_encode($specializations);
             exit;
         } else {
             http_response_code(400); // Bad Request
             echo json_encode(['error' => 'Doctor name is required']);
             exit;
         }
     }
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


   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }
}
