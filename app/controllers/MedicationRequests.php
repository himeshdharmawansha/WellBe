<?php

class MedicationRequests extends Controller
{
   protected $medicationRequestModel;

   public function __construct()
   {
      $this->medicationRequestModel = new MedicationRequest();
   }

   public function index()
   {

   }

   public function getRequestsJson()
   {
      $requests = $this->medicationRequestModel->getAll();
      header('Content-Type: application/json');
      echo json_encode($requests);
      exit;
   }

   public function searchRequestsByPatientId()
   {
      $searchTerm = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : '';

      if (!empty($searchTerm)) {
         $requests = $this->medicationRequestModel->searchByPatientId($searchTerm);
         header('Content-Type: application/json');
         echo json_encode($requests);
         exit;
      } else {
         echo json_encode(['error' => 'No patient ID provided.']);
         exit;
      }
   }

   public function update()
   {
      $rawData = file_get_contents("php://input");
      $decodedData = json_decode($rawData, true);

      if (!empty($decodedData)) {
         $requestID = $decodedData['requestID'] ?? null;
         $remarks = $decodedData['remarks'] ?? '';
         $medications = $decodedData['medications'] ?? [];

         if ($requestID) {
            $this->medicationRequestModel->updateRequest($requestID, $remarks, $medications);
            echo json_encode(['success' => true, 'message' => 'Request updated successfully.']);
         } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID.']);
         }
      } else {
         echo json_encode(['success' => false, 'message' => 'Invalid data.']);
      }
   }

   public function updateState()
   {
      $data = json_decode(file_get_contents("php://input"), true);

      if (!empty($data['requestID']) && isset($data['state'])) {
         $requestID = htmlspecialchars($data['requestID']);
         $newState = htmlspecialchars($data['state']);

         $this->medicationRequestModel->updateState($requestID, $newState);
         echo json_encode(['success' => true, 'message' => 'State updated successfully.']);
      } else {
         echo json_encode(['success' => false, 'error' => 'Invalid input.']);
      }
   }
}