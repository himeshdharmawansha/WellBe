<?php

class Receptionist extends Controller
{

   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'todayAppointments' => ["fas fa-calendar-alt", "Appointments"],
         'patients' => ["fas fa-user", "Patients"],
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
      $appointments = new Appointments();
      $patient = new Patient();
      $doctor = new Doctor();
      $pharmacist = new Pharmacy();
      $labTech = new Lab();
      $timeslot = new Timeslot();

      $data = [
         'todayAppointmentsCount' => $appointments->totalTodayAppointments(),
         'patientsCount'     => $patient->totalPatients(),
         'doctorsCount'      => $doctor->totalDoctors(),
         'pharmacistsCount'  => $pharmacist->totalPharmacists(),
         'labTechsCount'     => $labTech->totalLabTechs(),
         'todaySessions'     => $timeslot->getTodaySessions(),
      ];

      $this->view('Receptionist/dashboard', 'dashboard', $data);
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
               $patient_id = $app['patient_id'];

               $newPatientStatus = $app['patient_status'];
               $newPaymentStatus = $app['payment_status'];
               $originalPatientStatus = $app['original_patient_status'];
               $originalPaymentStatus = $app['original_payment_status'];
               $patient_type = $app['patient_type'];

               // Figure out if verified status has changed
               $original_verified = $app['original_verified'];
               $verified = isset($app['verified']) ? 'Verified' : 'Not Verified';
      
               // Only update if either status has changed
               if ($newPatientStatus !== $originalPatientStatus || $newPaymentStatus !== $originalPaymentStatus) {
                  error_log("Updating appointment ID: $id | Patient: $newPatientStatus | Payment: $newPaymentStatus");
                  $appointmentModel->updateStatus($id, $newPatientStatus, $newPaymentStatus, $slot_id, $doctor_id);
               }

              // Update verified status in patient table if changed
              if ($original_verified !== $verified) {
                  error_log("Updating verified status for patient ID: $patient_id to $verified");
                  $appointmentModel->updatePatientVerifiedStatus($patient_id, $verified);
               }
            }
         }
         // Redirect back after update
         header("Location: " . ROOT . "/Receptionist/todayAppointments"); 
         exit();
      }
      
      if ($slot_id && $doctor_id) {
         $appointments = new Appointments();
         $timeslot = new Timeslot();
         
         $data['session'] = $timeslot->getSessionData($slot_id, $doctor_id);
         $data['appointments'] = $appointments->getAppointmentsForSession($slot_id, $doctor_id);

      }
      $this->view('Receptionist/appointmentQueue', 'Appointments', $data);
      
   }

   public function appointmentsUpcoming()
   {
      $timeslot = new Timeslot(); 
      $data['today_sessions'] = $timeslot->getUpcomingSessions(); // Fetch all upcoming appointments
      //print_r($data['today_sessions']);
      
      $this->view('Receptionist/appointmentsUpcoming', 'Appointments', $data);
   }

   public function appointmentsPast()
   {
      $timeslot = new Timeslot(); 
      $data['today_sessions'] = $timeslot->getPastSessions(); // Fetch all past appointments
      //print_r($data['today_sessions']);
      
      $this->view('Receptionist/appointmentsPast', 'Appointments', $data);
   }

   public function patients()
   {
      $patient = new Patient(); // Instantiate the Patient model
      $data['patients'] = $patient->getAllPatients(); // Fetch all patient data, including ID

      $this->view('Receptionist/patients', 'patients', $data); // Pass the data to the view
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
            header('Location: ' . ROOT . '/Receptionist/patientForm2');
            exit;
         } 
         else {
            // Add validation errors to data array
            $data['errors'] = $patient->getErrors();
            $data['formData'] = $patientData; // Pass submitted data back to the view
         }
      }

      $this->view('Receptionist/patientForm1', 'patients', $data ?? []);
   }

   public function patientForm2()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $patient = new Patient();
         $user = new ProfileModel();

         // Merge previously stored data with current data
         $patientData = array_merge($_SESSION['patient_data'] ?? [], $_POST);

         // Validate step 2 fields
         if ($patient->validate($patientData, 2)) {
               // Add doctor to the database
               if ($user->addUser($patientData, 4) && $patient->addPatient($patientData)) {
                  echo "<script>
                        alert('Patient Profile Created Successfully!');
                        window.location.href = '" . ROOT . "/Receptionist/patients';
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

      $this->view('Receptionist/patientForm2', 'patients', $data ?? []);
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
                        window.location.href = '" . ROOT . "/Receptionist/patients';
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
                           window.location.href = '" . ROOT . "/Receptionist/patients';
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

      $this->view('Receptionist/patientProfile', 'patients', $data); // Pass data to the view
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
