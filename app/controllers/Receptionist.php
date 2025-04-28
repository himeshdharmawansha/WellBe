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
      $data['today_sessions'] = $timeslot->getTodaySessions(); 
      
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

               $original_verified = $app['original_verified'];
               $verified = isset($app['verified']) ? 'Verified' : 'Not Verified';
      
               if ($newPatientStatus !== $originalPatientStatus || $newPaymentStatus !== $originalPaymentStatus) {
                  error_log("Updating appointment ID: $id | Patient: $newPatientStatus | Payment: $newPaymentStatus");
                  $appointmentModel->updateStatus($id, $newPatientStatus, $newPaymentStatus, $slot_id, $doctor_id);
               }

              if ($original_verified !== $verified) {
                  error_log("Updating verified status for patient ID: $patient_id to $verified");
                  $appointmentModel->updatePatientVerifiedStatus($patient_id, $verified);
               }
            }
         }
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
      $data['today_sessions'] = $timeslot->getUpcomingSessions();
      
      $this->view('Receptionist/appointmentsUpcoming', 'Appointments', $data);
   }

   public function appointmentsPast()
   {
      $timeslot = new Timeslot(); 
      $data['today_sessions'] = $timeslot->getPastSessions(); 
      
      $this->view('Receptionist/appointmentsPast', 'Appointments', $data);
   }

   public function scheduleAppointment()
   {
      $data = [];
      $doctor = new Doctor(); 

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {

         $doctor_name = isset($_POST['doctor']) ? $_POST['doctor'] : '';
         $specialization = isset($_POST['specialization']) ? $_POST['specialization'] : '';

         $data['doctor_name'] = $doctor_name;

         if (empty($doctor_name) || empty($specialization)) {
            $data['error'] = "Please select a doctor.";
         } 
         else {
            $name = explode(" ", $doctor_name);

            $first_name = $name[0];
            $last_name = isset($name[1]) ? $name[1] : "";

            $docId = $doctor->getDoctorId($first_name, $last_name);
            $data['docId'] = $docId[0]->id;

            $timeslot = new Timeslot();

            $availableDates = $timeslot->getAvailableDays($docId[0]->id);

            $appointment = new Appointments();

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
      }

      $data['doctors'] = $doctor->getDoctorsWithSpecializations(); 

      $this->view('Receptionist/scheduleAppointment', 'Appointments', $data);
   }

   public function makeAppointment() 
   {
      $json = file_get_contents('php://input');
      $data = json_decode($json, true); 
  
      if ($data) {
          $appointmentModel = new Appointments();
          $patient = new Patient();
          $timeslot = new Timeslot();

          $data['patient_id'] = $patient->getPatientID($data['patient_nic']);
          $data['slot_id'] = $timeslot->getSlotID($data['day']);

          $result = $appointmentModel->createAppointment($data);
  
          if ($result) {
              echo json_encode(['status' => 'success', 'message' => 'Appointment created successfully']);
          } else {
              echo json_encode(['status' => 'error', 'message' => 'Failed to create appointment']);
          }
      } else {
          echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
      }
   }
   


   public function patients()
   {
      $patient = new Patient(); 
      $data['patients'] = $patient->getAllPatients(); 

      $this->view('Receptionist/patients', 'patients', $data);
   }

   public function patientForm1()
   {
      $data = [];

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $patient = new Patient();
         $patientData = $_POST;

         if ($patient->validate($patientData, 1)) {
            $_SESSION['patient_data'] = $patientData;
            header('Location: ' . ROOT . '/Receptionist/patientForm2');
            exit;
         } 
         else {
            $data['errors'] = $patient->getErrors();
            $data['formData'] = $patientData; 
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

         $patientData = array_merge($_SESSION['patient_data'] ?? [], $_POST);

         if ($patient->validate($patientData, 2)) {
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
               $data['errors'] = $patient->getErrors();
               $data['formData'] = $patientData; 
         }   
      }

      $this->view('Receptionist/patientForm2', 'patients', $data ?? []);
   }

   public function patientProfile()
   {
      $nic = $_GET['nic'] ?? null; 

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $action = $_POST['action'] ?? null;

         if ($action === 'delete') {
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
               $patientData = $_POST;

               $patient = new Patient();

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
                  $data['errors'] = $patient->getErrors();
               }

               $data['patientProfile'] = $patient->getPatientById($nic);
         }
      } elseif ($nic) {
         $patient = new Patient();
         $data['patientProfile'] = $patient->getPatientById($nic);

         if (empty($data['patientProfile'])) {
               $data['error'] = "Patient with NIC $nic not found.";
         }
      } else {
         $data['error'] = "No patient NIC provided.";
      }

      $this->view('Receptionist/patientProfile', 'patients', $data); 
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
