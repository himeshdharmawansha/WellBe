<?php

class Receptionist extends Controller
{

   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'appointmentsOngoing' => ["fas fa-calendar-alt", "Appointments"],
         'chat' => ["fas fa-comment-dots", "Chat"],
         'logout' => ["fas fa-sign-out-alt", "Logout"]
      ],
      'userType' => 'admin'
   ];

   public function index()
   {
      $this->view('Receptionist/dashboard', 'dashboard');
   }

   public function appointmentsOngoing()
   {
      $this->view('Receptionist/appointmentsOngoing', 'appointmentsOngoing');
   }

   public function appointmentsUpcoming()
   {
      $this->view('Receptionist/appointmentsUpcoming', 'appointmentsUpcoming');
   }

   public function appointmentsPast()
   {
      $this->view('Receptionist/appointmentsPast', 'appointmentsPast');
   }

   public function patients()
   {
      $this->view('Admin/patients', 'patients');
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
