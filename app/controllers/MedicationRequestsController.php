<?php

class MedicationRequestsController extends Controller
{
   protected $medicationRequestModel;

   public function __construct()
   {
      // Initialize the MedicationRequest model
      $this->medicationRequestModel = new MedicationRequest();
   }

   // Fetch all medication requests and pass them to the view
   public function index()
   {
      $pendingRequests = $this->medicationRequestModel->read("SELECT * FROM medication_requests WHERE state = 'pending'");
      $progressRequests = $this->medicationRequestModel->read("SELECT * FROM medication_requests WHERE state = 'progress'");
      $completedRequests = $this->medicationRequestModel->read("SELECT * FROM medication_requests WHERE state = 'completed'");

      $this->view('Pharmacy/requests', [
         'pendingRequests' => $pendingRequests,
         'progressRequests' => $progressRequests,
         'completedRequests' => $completedRequests,
      ]);
   }

   // API endpoint to retrieve requests as JSON for AJAX
   public function getRequestsJson()
   {
      $requests = $this->medicationRequestModel->findAll();
      header('Content-Type: application/json');
      echo json_encode($requests);
      exit;
   }
}
