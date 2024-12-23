<?php

class Lab extends Controller
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
      'userType' => 'lab'
   ];

   public function __construct()
        {
            if(!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "lab"){
                redirect('login');
                exit;
            }
        }

   public function index()
   {
      $this->view('Lab/dashboard', 'dashboard');
   }

   public function requests()
   {
      $this->view('Lab/requests', 'requests');
   }

   public function chat()
   {
      $this->view('Lab/chat', 'chat');
   }
   public function labTestDetails()
   {
      $this->view('Lab/labTestDetails', 'requests');
   }
   public function login()
   {
      $this->view('Lab/login', 'login');
   }
   public function logout()
   {
      $this->view('Lab/logout', 'logout');
   }
   public function report()
   {
      $this->view('Pharmacy/report', 'report');
   }

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];

      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }

   public function getRequestsByDay()
   {
      $db = new Database();
      $query = "
        SELECT 
            DAYNAME(date) as day, 
            COUNT(*) as count
        FROM test_requests
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

   public function getRequestCounts()
   {
      $db = new Database();
      $query = "
           SELECT state, COUNT(*) as count 
           FROM test_requests 
           WHERE date >= NOW() - INTERVAL 14 DAY
           GROUP BY state
       ";

      $results = $db->read($query);

      // Structure the data for easier use in the frontend
      $response = [
         'pending' => 0,
         'ongoing' => 0,
         'completed' => 0,
      ];

      foreach ($results as $row) {
         if (isset($response[$row['state']])) {
            $response[$row['state']] = (int)$row['count'];
         }
      }

      echo json_encode($response);
   }

   public function testRequests()
   {
      // Database connection
      $db = new Database();

      // Query to fetch the medication requests
      $query = "SELECT patient_id, state 
                  FROM test_requests 
                  WHERE state IN ('ongoing', 'pending') 
                  ORDER BY FIELD(state, 'ongoing', 'pending') 
                  LIMIT 20";

      // Execute the query
      $requests = $db->query($query);

      // Return the result as JSON for AJAX
      echo json_encode($requests);
   }

   public function fetchNewMessages()
   {
       $db = new Database();
       $sender = $_SESSION['userid']; // Current user ID
   
       // Step 1: Get IDs of users with role = 3
       $receiverQuery = "SELECT id FROM user_profile WHERE role = 3";
       $receivers = $db->query($receiverQuery);
   
       if (!$receivers) {
           echo json_encode("No receivers found"); // No receivers found
           return;
       }
   
       // Extract receiver IDs
       $receiverIds = array_map(fn($r) => $r->id, $receivers);
   
       // Prepare placeholders for the IN clause (for the receiver IDs)
       $placeholders = implode(',', array_fill(0, count($receiverIds), '?'));
   
       // Step 2: Fetch unique receivers with their latest message date
       $messageQuery = "
           SELECT 
               a.first_name,
               MAX(m.date) AS last_message_date  
           FROM message m
           JOIN administrative_staff a ON m.sender = a.id
           WHERE m.receiver = ?  
             AND m.sender IN ($placeholders)  
             AND m.seen = 0 
             AND m.received = 1
           GROUP BY m.sender, a.first_name  
           ORDER BY last_message_date DESC
           LIMIT 20";
   
       // Merge sender and receiver IDs into a single parameters array
       $params = array_merge([$sender], $receiverIds);
   
       // Execute the query
       $messages = $db->readn($messageQuery, $params);
   
       // Step 3: Return results as JSON
       if ($messages === false) {
           echo json_encode([]); // No messages found
           return;
       }
   
       echo json_encode($messages);
   }
}

