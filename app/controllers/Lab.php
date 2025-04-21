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

   public function __construct()
   {
      if (!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "lab") {
         redirect('login');
         exit;
      }
      $this->labModel = new LabModel(); 
   }

   public function index()
   {
      // Fetch request counts from the model
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