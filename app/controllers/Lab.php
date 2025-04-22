<?php

class Lab extends Controller
{
   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'requests' => ["fas fa-list", "Requests"],
         'chat' => ["fa-solid fa-comment-dots", "Chat"],
         'logout' => ["fas fa-sign-out-alt", "Logout"]
      ],
      'userType' => 'lab'
   ];

   private $labModel;
   private $testRequestModel;
   private $profileModel;
   private $chatModel;

   public function __construct()
   {
      if (!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "lab") {
         redirect('login');
         exit;
      }
      $this->labModel = new LabModel();
      $this->testRequestModel = new TestRequest();
      $this->profileModel = new ProfileModel();
      $this->chatModel = new Chat();
   }

   private function UnseenCounts($roles)
   {
      if (empty($roles)) {
         return ['error' => 'Invalid or missing roles parameter'];
      }

      try {
         $result = $this->chatModel->getUnseenCounts($roles);
         return $result;
      } catch (Exception $e) {
         return ['error' => $e->getMessage()];
      }
   }

   public function index()
   {
      $counts = $this->labModel->getRequestCounts();
      $data = [
         'counts' => [
            'pending' => $counts['pending'] ?? 0,
            'ongoing' => $counts['ongoing'] ?? 0,
            'completed' => $counts['completed'] ?? 0
         ]
      ];
      $this->view('Lab/dashboard', 'dashboard', $data);
   }

   public function requests()
   {
      $requests = $this->testRequestModel->getAll();
      $pendingRequests = array_filter($requests, function ($request) {
         return $request['state'] === 'pending';
      });
      $ongoingRequests = array_filter($requests, function ($request) {
         return $request['state'] === 'ongoing';
      });
      $completedRequests = array_filter($requests, function ($request) {
         return $request['state'] === 'completed';
      });

      $data = [
         'pendingRequests' => array_values($pendingRequests),
         'ongoingRequests' => array_values($ongoingRequests),
         'completedRequests' => array_values($completedRequests)
      ];

      $this->view('Lab/requests', 'requests', $data);
   }

   public function getRequestsJson()
   {
      $requests = $this->testRequestModel->getAll();
      header('Content-Type: application/json');
      echo json_encode($requests);
      exit;
   }

   public function searchRequestsByPatientId()
   {
      $patientId = $_GET['patient_id'] ?? '';
      $results = $this->testRequestModel->searchByPatientId($patientId);
      header('Content-Type: application/json');
      echo json_encode($results);
      exit;
   }

   public function chat()
   {
      // Fetch unseen counts using the local UnseenCounts method
      $unseenCounts = $this->UnseenCounts([3, 5]);
      $user_profile = $unseenCounts;
      if (!is_array($user_profile)) {
         $user_profile = [];
      }

      // Fetch all profiles
      $profiles = $this->profileModel->getAll();
      if (!empty($profiles) && !isset($profiles['error'])) {
         $profileMap = [];
         foreach ($profiles as $profile) {
            $profileMap[$profile->id] = $profile;
         }
         foreach ($user_profile as &$user) {
            if (isset($user['id']) && isset($profileMap[$user['id']])) {
               $user['image'] = ROOT . '/assets/images/users/' . $profileMap[$user['id']]->image;
            } else {
               $user['image'] = ROOT . '/assets/images/users/Profile_default.png';
            }
         }
         unset($user);
      }

      // Pass data to the view
      $data = [
         'user_profile' => $user_profile
      ];
      $this->view('Lab/chat', 'chat', $data);
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

   public function renderComponent($component, $active)
   {
      $elements = $this->data['elements'];
      $userType = $this->data['userType'];
      $filename = "../app/views/Components/{$component}.php";
      require $filename;
   }

   public function getRequestsByDay()
   {
      $requestsByDay = $this->labModel->getRequestsByDay();
      echo json_encode($requestsByDay);
   }

   public function getRequestCounts()
   {
      $counts = $this->labModel->getRequestCounts();
      echo json_encode($counts);
   }

   public function testRequests()
   {
      $requests = $this->labModel->getTestRequests();
      echo json_encode($requests);
   }

   public function fetchNewMessages()
   {
      $messages = $this->labModel->fetchNewMessages();
      echo json_encode($messages);
   }
}