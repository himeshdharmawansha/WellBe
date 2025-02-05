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
      $patient = new Patient(); // Instantiate the Patient model
      $data['patients'] = $patient->getAllPatients(); // Fetch all patient data, including ID

      $this->view('Admin/patients', 'patients', $data); // Pass the data to the view
   }

   public function patientForm1()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $patient = new Patient();
         $patientData = $_POST;

         // Validate step 1 fields
         if ($patient->validate($patientData, 1)) {
            // Temporarily store validated data in session
            $_SESSION['patient_data'] = $patientData;
            header('Location: ' . ROOT . '/Admin/patientForm2');
            exit;
         } 
         else {
            // Add validation errors to data array
            $data['errors'] = $patient->getErrors();
            $data['formData'] = $patientData; // Pass submitted data back to the view
         }
      }

      $this->view('Admin/patientForm1', 'patients', $data ?? []);
   }

   public function patientForm2()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $patient = new Patient();

         // Merge previously stored data with current data
         $patientData = array_merge($_SESSION['patient_data'] ?? [], $_POST);

         // Validate step 2 fields
         if ($patient->validate($patientData, 2)) {
               // Add doctor to the database
               if ($patient->addPatient($patientData)) {
                  echo "<script>
                        alert('Patient Profile Created Successfully!');
                        window.location.href = '" . ROOT . "/Admin/patients';
                  </script>";
                  unset($_SESSION['patient_data']); 
                  exit;
               } else {
                  echo "<script>alert('Database insertion failed.');</script>";
               }
         } else {
               // Add validation errors to data array
               $data['errors'] = $patient->getErrors();
               $data['formData'] = $patientData; // Pass submitted data back to the view
         }   
      }

      $this->view('Admin/patientForm2', 'patients', $data ?? []);
   }

   public function patientProfile()
   {
      $nic = $_GET['nic'] ?? null; // Fetch NIC from query string

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $action = $_POST['action'] ?? null;

         if ($action === 'delete') {
            // Handle delete action
            if ($nic) {
               $patient = new Patient();

               if ($patient->deletePatient($nic)) {
                  echo "<script>
                        alert('Patient profile deleted successfully!');
                        window.location.href = '" . ROOT . "/Admin/patients';
                  </script>";
               } else {
                  echo "<script>
                        alert('Failed to delete the patient profile.');
                  </script>";
               }
            }
               
         } else if($action === 'update') {
               // Handle update logic
               $patientData = $_POST;

               // Instantiate the Doctor model
               $patient = new Patient();

               // Debugging: Check submitted data
               echo(print_r($patientData, true));

               // Validate the input data
               if ($patient->validatePatient($patientData)) {
                  echo "validated";
                  if ($patient->updatePatient($patientData, $nic)) {
                     echo "updated";
                     echo "<script>
                           alert('Patient Profile Updated Successfully!');
                           window.location.href = '" . ROOT . "/Admin/patients';
                     </script>";
                  } else {
                     echo "<script>
                           alert('Failed to update patient profile.');
                     </script>";
                  }
               } else {
                  // Retrieve validation errors
                  $data['errors'] = $patient->getErrors();
               }

               // Reload patient profile after submission
               $data['patientProfile'] = $patient->getPatientById($nic);
         }
      } elseif ($nic) {
         // Fetch patient profile for the given NIC
         $patient = new Patient();
         $data['patientProfile'] = $patient->getPatientById($nic);

         if (empty($data['patientProfile'])) {
               $data['error'] = "Patient with NIC $nic not found.";
         }
      } else {
         $data['error'] = "No patient NIC provided.";
      }

      $this->view('Admin/patientProfile', 'patients', $data); // Pass data to the view
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

      $this->view('Admin/doctorForm1', 'doctors', $data ?? []);
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
                  unset($_SESSION['doctor_data']); 
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
               //echo(print_r($doctorData, true));

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
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $pharmacist = new Pharmacy();
         $pharmacistData = $_POST;

         // Validate step 1 fields
         if ($pharmacist->formValidate($pharmacistData, 1)) {
            // Temporarily store validated data in session
            echo "step01";
            $_SESSION['pharmacist_data'] = $pharmacistData;
            header('Location: ' . ROOT . '/Admin/pharmacistForm2');
            exit;
         } 
         else {
            // Add validation errors to data array
            $data['errors'] = $pharmacist->getErrors();
            $data['formData'] = $pharmacistData; // Pass submitted data back to the view
         }
      }

      $this->view('Admin/pharmacistForm1', 'pharmacists', $data ?? []);
   }

   public function pharmacistForm2()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $pharmacist = new Pharmacy();

         // Merge previously stored data with current data
         $pharmacistData = array_merge($_SESSION['pharmacist_data'] ?? [], $_POST);

         // Validate step 2 fields
         if ($pharmacist->formValidate($pharmacistData, 2)) {
               echo "validated";
               // Debugging: Check submitted data
               echo(print_r($pharmacistData, true));
               // Add doctor to the database
               if ($pharmacist->addPharmacist($pharmacistData)) {
                  echo "<script>
                        alert('Pharmacist Profile Created Successfully!');
                        window.location.href = '" . ROOT . "/Admin/pharmacists';
                  </script>";
                  unset($_SESSION['pharmacist_data']); 
                  exit;
               } else {
                  echo "<script>alert('Database insertion failed.');</script>";
               }
         } else {
               // Add validation errors to data array
               $data['errors'] = $pharmacist->getErrors();
               $data['formData'] = $pharmacistData; // Pass submitted data back to the view
         }   
      }

      $this->view('Admin/pharmacistForm2', 'pharmacists', $data ?? []);
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
