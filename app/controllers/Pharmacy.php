<?php

class Pharmacy extends Controller
{

   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'requests' => ["fas fa-list", "Requests"],
         'chat' => ["fa-solid fa-comment-dots", "Chat"],
         'report' => ["fa-solid fa-chart-simple", "Report"],
         'setting' => ["fa-solid fa-gear", "Setting"],
         'logout' => ["fas fa-sign-out-alt", "Logout"]
      ],
      'userType' => 'pharmacy'
   ];

   public function __construct()
   {
      if (!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "pharmacy") {
         redirect('login');
         exit;
      }
   }

   public function index()
   {
      $this->view('Pharmacy/dashboard', 'dashboard');
   }

   public function requests()
   {
      $this->view('Pharmacy/requests', 'requests');
   }

   public function chat()
   {
      $this->view('Pharmacy/chat', 'chat');
   }
   public function report()
   {
      $this->view('Pharmacy/report', 'report');
   }

   public function medicationDetails()
   {
      $this->view('Pharmacy/medicationDetails', 'requests');
   }

   public function login()
   {
      $this->view('Lab/login', 'login');
   }
   public function logout()
   {
      $this->view('Lab/logout', 'logout');
   }

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }
   public function getRequestCounts()
   {
      $db = new Database();
      $query = "
           SELECT state, COUNT(*) as count 
           FROM medication_requests 
           WHERE date >= NOW() - INTERVAL 14 DAY
           GROUP BY state
       ";

      $results = $db->read($query);

      // Structure the data for easier use in the frontend
      $response = [
         'pending' => 0,
         'progress' => 0,
         'completed' => 0,
      ];

      foreach ($results as $row) {
         if (isset($response[$row['state']])) {
            $response[$row['state']] = (int)$row['count'];
         }
      }

      echo json_encode($response);
   }

   public function getRequestsByDay()
   {
      $db = new Database();
      $query = "
        SELECT 
            DAYNAME(date) as day, 
            COUNT(*) as count
        FROM medication_requests
        WHERE date >= NOW() - INTERVAL 7 DAY
        GROUP BY DAYNAME(date)
        ORDER BY FIELD(DAYNAME(date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ";

      $results = $db->read($query);

      // Initialize days of the week
      $days = ['Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0];

      // Populate data from the query
      foreach ($results as $row) {
         $days[$row['day']] = (int)$row['count'];
      }

      // Return the counts in a simple format
      echo json_encode(array_values($days));
   }
}
