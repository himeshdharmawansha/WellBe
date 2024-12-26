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
      $this->view('Admin/appointmentsOngoing', 'appointments');
   }

   public function appointmentsUpcoming()
   {
      $this->view('Admin/appointmentsUpcoming', 'appointments');
   }

   public function appointmentsPast()
   {
      $this->view('Admin/appointmentsPast', 'appointments');
   }

   public function patients()
   {
      $this->view('Admin/patients', 'patients');
   }

   public function patientForm1()
   {
      $this->view('Admin/patientForm1', 'patient');
   }

   public function patientForm2()
   {
      $this->view('Admin/patientForm2', 'patient');
   }

   public function doctors()
   {
      $doctor = new Doctor(); // Instantiate the Doctor model
      $data['doctors'] = $doctor->getAllDoctors(); // Fetch all doctor data, including ID
      $this->view('Admin/doctors', 'doctors', $data); // Pass the data to the view
   }

   public function doctorForm1()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $doctor = new Doctor();
         $doctorData = $_POST;

         // Validate step 1 fields
         if ($doctor->validate($doctorData, 1)) {
            // Temporarily store validated data in session
            $_SESSION['doctor_data'] = $doctorData;
            header('Location: ' . ROOT . '/Admin/doctorForm2');
            exit;
         } 
         else {
            // Add validation errors to data array
            $data['errors'] = $doctor->getErrors();
            $data['formData'] = $doctorData; // Pass submitted data back to the view
         }
      }

      $this->view('Admin/doctorForm1', 'doctorForm1', $data ?? []);
   }

   public function doctorForm2()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $doctor = new Doctor();

         // Merge previously stored data with current data
         $doctorData = array_merge($_SESSION['doctor_data'] ?? [], $_POST);

         // Validate step 2 fields
         if ($doctor->validate($doctorData, 2)) {
               // Add doctor to the database
               if ($doctor->addDoctor($doctorData)) {
                  echo "<script>
                        alert('Doctor Profile Created Successfully!');
                        window.location.href = '" . ROOT . "/Admin/doctors';
                  </script>";
                  unset($_SESSION['doctor_data']); // Clear session data
                  exit;
               } else {
                  echo "<script>alert('Database insertion failed.');</script>";
               }
         } else {
               // Add validation errors to data array
               $data['errors'] = $doctor->getErrors();
               $data['formData'] = $doctorData; // Pass submitted data back to the view
         }   
      }

      $this->view('Admin/doctorForm2', 'doctors', $data ?? []);
   }
   
   public function doctorProfile()
   {
      $nic = $_GET['nic'] ?? null; // Fetch NIC from query string

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $action = $_POST['action'] ?? null;

         if ($action === 'delete') {
            // Handle delete action
            if ($nic) {
               $doctor = new Doctor();

               if ($doctor->deleteDoctor($nic)) {
                  echo "<script>
                        alert('Doctor profile deleted successfully!');
                        window.location.href = '" . ROOT . "/Admin/doctors';
                  </script>";
               } else {
                  echo "<script>
                        alert('Failed to delete the doctor profile.');
                  </script>";
               }
            }
               
         } else if($action === 'update') {
               // Handle update logic
               $doctorData = $_POST;

               // Instantiate the Doctor model
               $doctor = new Doctor();

               // Debugging: Check submitted data
               // echo(print_r($doctorData, true));

               // Validate the input data
               if ($doctor->validateDoctor($doctorData)) {
                  if ($doctor->updateDoctor($doctorData, $nic)) {
                     echo "<script>
                           alert('Doctor Profile Updated Successfully!');
                           window.location.href = '" . ROOT . "/Admin/doctors';
                     </script>";
                  } else {
                     echo "<script>
                           alert('Failed to update doctor profile.');
                     </script>";
                  }
               } else {
                  // Retrieve validation errors
                  $data['errors'] = $doctor->getErrors();
               }

               // Reload doctor profile after submission
               $data['doctorProfile'] = $doctor->getDoctorById($nic);
         }
      } elseif ($nic) {
         // Fetch doctor profile for the given NIC
         $doctor = new Doctor();
         $data['doctorProfile'] = $doctor->getDoctorById($nic);

         if (empty($data['doctorProfile'])) {
               $data['error'] = "Doctor with NIC $nic not found.";
         }
      } else {
         $data['error'] = "No doctor NIC provided.";
      }

      $this->view('Admin/doctorProfile', 'doctors', $data); // Pass data to the view
   }

   public function pharmacists()
   {
      $this->view('Admin/pharmacists', 'pharmacists');
   }

   public function pharmacistForm1()
   {
      $this->view('Admin/pharmacistForm1', 'pharmacists');
   }

   public function pharmacistForm2()
   {
      $this->view('Admin/pharmacistForm2', 'pharmacists');
   }

   public function labTechs()
   {
      $this->view('Admin/labTechs', 'Lab Technicians');
   }

   public function labTechForm1()
   {
      $this->view('Admin/labTechForm1', 'Lab Technicians');
   }

   public function labTechForm2()
   {
      $this->view('Admin/labTechForm2', 'Lab Technicians');
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
