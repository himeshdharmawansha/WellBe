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
      $data = [];
      $doctor = new Doctor(); // Instantiate the Doctor model

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         
         $doctor_name = isset($_POST['doctor']) ? $_POST['doctor'] : '';
         $specialization = isset($_POST['specialization']) ? $_POST['specialization'] : '';
 
         $data['doctor_name'] = $doctor_name;

         if (empty($doctor_name) || empty($specialization)) {
             $data['error'] = "Please select a doctor.";
         } else {

            $name = explode(" ",$doctor_name);

            $first_name = $name[0];
            $last_name = isset($name[1]) ? $name[1] : "";

            $docId = $doctor->getDoctorId($first_name,$last_name);
            //echo $docId[0]->id;

            $timeslot = new Timeslot();

            //get available dates of a doctor
            $availableDates = $timeslot->getAvailableDays($docId[0]->id);

            $appointment = new Appointments();

            //get current appointment num
            $appointmentNums = $appointment->getAppointment($docId[0]->id,$availableDates['todayId']);

            foreach($availableDates['matchedDates'] as &$day){
               $isScheduled = NULL;
               foreach($appointmentNums as $appointmentNum){
                  if($day['slot_id'] === $appointmentNum->date){
                     $isScheduled = $appointmentNum->appointment_id+1;
                  }
               }
               if(!$isScheduled){
                  $isScheduled = 1;
               }
               $day['appointment_id'] = $isScheduled;
            }

            $data['dates'] = $availableDates['matchedDates'];
         }
      }

      $data['doctors'] = $doctor->getDoctorsWithSpecializations(); // Fetch all doctor name


      $this->view('Patient/doc_appointment', 'doc_appointment', $data);

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

   public function generatehash()
   {
      $order_id = $_POST['order_id'];
      $amount = 3000;
      $merchant_id = "1228628";
      $merchant_secret = "MzkxMDUxMDYzNzIxMTExNDMyOTMyMDQ1NTQ0ODU3MzM1MTk3MDU4NA==";
      $currency = "LKR";

      $hash = strtoupper(
         md5(
            $merchant_id .
            $order_id .
            number_format($amount, 2, '.', '') .
            $currency .
            strtoupper(md5($merchant_secret))
         )
      );

      $array = [];

      $array["first_name"] = "Amrah";
      $array["last_name"] = "Slamath";
      $array["email"] = "amrah@gmail.com";
      $array["phone"] = "07712345672";
      $array["address"] = "No. 123, ABC road";
      $array["city"] = "Colombo";
      $array["country"] = "Sri Lanka";
      $array["delivery_address"] = "No. 173, ABD road";
      $array["delivery_city"] = "Jaffna";
      $array["delivery_country"] = "Sri Lanka";
      $array["items"] = "Appointment";
      $array["amount"] = $amount;
      $array["merchant_id"] = $merchant_id;
      $array["order_id"] = $order_id;
      $array["merchant_secret"] = $merchant_secret;
      $array["currency"] = $currency;
      $array["hash"] = $hash;

      $jsonObj = json_encode($array);

      echo $jsonObj;
   }



   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }

   public function getPaymentData()
    {

//coming from view form
        try {
            $merchant_id         = $_POST['merchant_id'];
            // $assignment_id       = $_POST['custom_1'];
            $order_id            = $_POST['order_id'];
            $payhere_amount      = $_POST['payhere_amount'];
            $payhere_currency    = $_POST['payhere_currency'];
            $status_code         = $_POST['status_code'];
            $md5sig              = $_POST['md5sig'];

            $merchant_secret = $_ENV['PAYHERE_SECRET']; // Replace with your Merchant Secret

            $local_md5sig = strtoupper(
                md5(
                    $merchant_id .
                        $order_id .
                        $payhere_amount .
                        $payhere_currency .
                        $status_code .
                        strtoupper(md5($merchant_secret))
                )
            );

            if (($local_md5sig === $md5sig) and ($status_code === "2")) {
                $payment = new Payment();//my model name
                $result = $payment->addPayment($order_id, $payhere_amount, $payhere_currency, $status_code);
                if($result == true){
                header('Content-Type:application/json');
                echo json_encode(["status" => "success", "message" => "Payment Successfully Recorded"]);
                }
                else{
                header('Content-Type:application/json');
                echo json_encode(["status" => "failed", "message" => "Payment Failed to  Record"]);

                }
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['error' => 'Invalid Payment Details or Signature']);
            }
        } catch (Exception $e) {
            error_log($e);
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error fetching Data']);
        }
      }
}
