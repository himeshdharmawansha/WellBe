<?php

class Admin extends Controller
{

   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'appointmentsOngoing' => ["fas fa-calendar-alt", "Appointments"],
         'patients' => ["fas fa-user", "Patients"],
         'doctors' => ["fas fa-user-md", "Doctors"],
         'pharmacists' => ["fas fa-pills", "Pharmacists"],
         'labTechs' => ["fas fa-vials", "Lab Technicians"],
         'chat' => ["fas fa-comment-dots", "Chat"],
         'logout' => ["fas fa-sign-out-alt", "Logout"]
      ],
      'userType' => 'admin'
   ];

   public function __construct()
        {
            if(!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "admin"){
                redirect('login');
                exit;
            }
        }

   public function index()
   {
      $this->view('Admin/dashboard', 'dashboard');
   }

   public function appointmentsOngoing()
   {
      $this->view('Admin/appointmentsOngoing', 'appointmentsOngoing');
   }

   public function appointmentsUpcoming()
   {
      $this->view('Admin/appointmentsUpcoming', 'appointmentsUpcoming');
   }

   public function appointmentsPast()
   {
      $this->view('Admin/appointmentsPast', 'appointmentsPast');
   }

   public function patients()
   {
      $this->view('Admin/patients', 'patients');
   }

   public function patientForm1()
   {
      $this->view('Admin/patientForm1', 'patientForm1');
   }

   public function patientForm2()
   {
      $this->view('Admin/patientForm2', 'patientForm2');
   }

   public function doctors()
   {
      $this->view('Admin/doctors', 'doctors');
   }

   public function doctorForm1()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         // Debug: Print or log POST data
         echo 'Form1 Data';
         echo '<pre>';
         print_r($_POST);
         echo '</pre>';

         $_SESSION['doctor_data'] = $_POST; // Temporarily store form data in session
         header('Location: ' . ROOT . '/Admin/doctorForm2');
         exit;
      }

      $this->view('Admin/doctorForm1', 'doctorForm1');
   }

   public function doctorForm2()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $doctorData = array_merge($_SESSION['doctor_data'] ?? [], $_POST);

         // // Debug: Print or log the merged doctor data
         // echo 'Form2 Data';
         // echo '<pre>';
         // print_r($doctorData);
         // echo '</pre>';

         $doctor = new Doctor();

         if ($doctor->validate($doctorData)) {
            if ($doctor->addDoctor($doctorData)) {
               echo "<script>
                      alert('Doctor Profile Created Successfully!');
                      window.location.href = '" . ROOT . "/Admin/doctors';
               </script>";
               exit; // Ensure the script stops execution

               unset($_SESSION['doctor_data']); // Clear session data after success     
            } else {
               echo "<script>alert('Database insertion failed.');</script>";
            }
         } else {
            // Show all validation errors as alerts
            foreach ($doctor->getErrors() as $error) {
               echo "<script>alert('$error');</script>";
            }
         }
      }

      $this->view('Admin/doctorForm2', 'doctorForm2', $data ?? []);
   }

   public function pharmacists()
   {
      $this->view('Admin/pharmacists', 'pharmacists');
   }

   public function pharmacistForm1()
   {
      $this->view('Admin/pharmacistForm1', 'pharmacistForm1');
   }

   public function pharmacistForm2()
   {
      $this->view('Admin/pharmacistForm2', 'pharmacistForm2');
   }

   public function labTechs()
   {
      $this->view('Admin/labTechs', 'labTechs');
   }

   public function labTechForm1()
   {
      $this->view('Admin/labTechForm1', 'labTechForm1');
   }

   public function labTechForm2()
   {
      $this->view('Admin/labTechForm2', 'labTechForm2');
   }

   public function chat()
   {
      $this->view('Admin/chat', 'chat');
   }

   public function logout()
   {
      $this->view('Admin/logout', 'logout');
   }

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }
}
