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
            if(!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "pharmacy"){
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
      $this->view('Pharmacy/medicationDetails', 'medicationDetails');
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
}
