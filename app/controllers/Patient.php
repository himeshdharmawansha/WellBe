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
         'chat' => ["fas fa-comments", "Chat"],
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

      //get rescheduled appointments
      $rescheduledAppointments = $appointmentsModel->getRescheduledApppointments($patient_id);

      $appointments = $appointmentsModel->getAllAppointmentsForPatient($patient_id);

      $ewalletArray = $appointmentsModel->getEWallet($patient_id);
      if (!$ewalletArray || empty($ewalletArray[0]['e_wallet'])) {
          $ewalletAmount = (object)['e_wallet' => 0];
      } else {
          $ewalletAmount = (object)['e_wallet' => $ewalletArray[0]['e_wallet']];
      }      

      // Pass appointment data to the dashboard view
      $this->view('Patient/patient_dashboard', 'patient_dashboard', [
         'appointments' => $appointments,
         'rescheduledAppointments' => $rescheduledAppointments,
         'ewalletAmount' => $ewalletAmount
     ]);
   }     


   public function medicalreports()
   {
      $medicalRecord = new MedicalRecord();
      $patientId = $_SESSION['USER']->id;
      $pastRecords = $medicalRecord->getRequest($patientId);
      //print_r($pastRecords);
      $data['pastRecords'] = $pastRecords;
      $this->view('Patient/medicalreports', 'medicalreports', $data);
   }


   public function reschedule()
   {
      $this->view('Patient/reschedule', 'reschedule');
   }

   public function labreports()
   {
      $labTest = new LabTest();
      $patientId = $_SESSION['USER']->id;
      $labRequests = $labTest->getRequest($patientId);

      // Filter unique requests based on id
      $uniqueRequests = [];
      $seenIds = [];
      foreach ($labRequests as $request) {
         if (!in_array($request->id, $seenIds)) {
            $seenIds[] = $request->id;
            $uniqueRequests[] = $request;
         }
      }

      $data['labRequests'] = $uniqueRequests;
      $this->view('Patient/labreports', 'labreports', $data);
   }


   public function reschedule_doc_appointment($id)
   {

      $data = [];
      $doctor = new Doctor(); // Instantiate the Doctor model

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {

         if (isset($_POST['specialization'])) {
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
         } else {
            $newDocId = $_POST['doc_id'] ?? null;
            $newAppointmentId = $_POST['appointment_id'] ?? null;
            $newDate = $_POST['day'] ?? null;

            $timeslot = new Timeslot();
            $newDateSlotId = $timeslot->getDateId($newDate);
            print_r($newDateSlotId);
            //print_r($_POST);

            $appointment = new Appointments();
            $appointment->rescheduleAppointment($id, $newDocId, $newAppointmentId, $newDateSlotId[0]->slot_id);
         }
      }

      $data['doctors'] = $doctor->getDoctorsWithSpecializations(); // Fetch all doctor name

      //print_r($data['doctors']);

      $this->view('Patient/reschedule_doc_appointment', 'doc_appointment', $data);
   }

   public function refund($id)
   {
      $payment = new Payment();
      $payment->refund();

      $appointment = new Appointments();
      $appointment->deleteAppointment($id);

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
      $patient = $_SESSION['USER'] ?? null;

      if ($patient && isset($patient->id)) {
         $patient_id = $patient->id;

         $medicalRecord = new MedicalRecord();

         // Get all requests made by the patient
         $requests = $medicalRecord->getRequest($patient_id);

         // Load details of the first request by default (if exists)
         $medDetails = null;
         if (!empty($requests)) {
            $firstReqId = $requests[0]->id;
            $medDetails = $medicalRecord->getMed($firstReqId);
         }

         // Pass data to the view
         $this->view('Patient/medical_rec', 'medical_rec', [
            'requests' => $requests,
            'medDetails' => $medDetails
         ]);
      } else {
         $this->view('Patient/medical_rec', 'medical_rec', ['error' => 'User not logged in.']);
      }
   }





   public function Lab_download($request_id = null)
   {
      $labTest = new LabTest();
      $patientId = $_SESSION['USER']->id;

      if ($request_id) {
         // Fetch test details for the specific request_id
         $labReports = $labTest->getTest($patientId);
         // Filter reports to include only those matching the request_id
         $filteredReports = array_filter($labReports, function ($report) use ($request_id) {
            return $report->id == $request_id;
         });
         $data['labReports'] = $filteredReports;
         $data['request_id'] = $request_id;
      } else {
         $data['labReports'] = [];
         $data['error'] = "No test request selected.";
      }

      $this->view('Patient/lab_download', 'Lab_download', $data);
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

      //print_r($data);
      $message = "You have successfully placed an appointment with Dr. " . $data['doctor'] . " on " . $data['appointment_date'] . ". Your appointment number is: " . $data['appointment_number'] . ".";

       $email = new Email();
       $email->send(
          "Wellbe",
          "wellbe@gmail.com",
          $message,
          $_SESSION['USER']->email,
       );

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
