<?php

class MedicationRequests extends Controller
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

      $this->view('Pharmacy/requests','requests' ,[
         'pendingRequests' => $pendingRequests,
         'progressRequests' => $progressRequests,
         'completedRequests' => $completedRequests,
      ]);
   }

   // API endpoint to retrieve requests as JSON for AJAX
   public function getRequestsJson()
   {
      $requests = $this->medicationRequestModel->getAll();
      header('Content-Type: application/json');
      echo json_encode($requests);
      exit;
   }

   // API endpoint to search medication requests by Patient ID
   public function searchRequestsByPatientId()
   {
      $searchTerm = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : '';

      if (!empty($searchTerm)) {
         // Search for medication requests based on the patient_id
         $query = "SELECT * FROM medication_requests WHERE patient_id LIKE :patient_id ORDER BY id DESC";
         $params = [
            ':patient_id' => '%' . $searchTerm . '%',
         ];

         $requests = $this->medicationRequestModel->read($query, $params);

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
      // Retrieve the raw POST data
      $rawData = file_get_contents("php://input");
      $decodedData = json_decode($rawData, true);

      if (!empty($decodedData)) {
         $requestID = $decodedData['requestID'] ?? null;
         $remarks = $decodedData['remarks'] ?? '';
         $medications = $decodedData['medications'] ?? [];

         if ($requestID) {
            $db = new Database();

            // Update the `medication_requests` table to mark the state as "completed"
            $updateRequestQuery = "UPDATE medication_requests SET state = 'completed', remark = :remarks WHERE id = :requestID";
            $requestParams = [
               ':remarks' => $remarks,
               ':requestID' => $requestID,
            ];
            $db->write($updateRequestQuery, $requestParams);

            // Update the `medication_request_details` table for each medication
            foreach ($medications as $medication) {
               $updateDetailQuery = "UPDATE medication_request_details 
                                        SET state = :state 
                                        WHERE req_id = :requestID AND medication_name = :medicationName";
               $detailParams = [
                  ':state' => $medication['state'],
                  ':requestID' => $requestID,
                  ':medicationName' => $medication['medicationName'],
               ];
               $db->write($updateDetailQuery, $detailParams);
            }

            // Return a JSON response
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

         $query = "UPDATE medication_requests SET state = :state WHERE id = :id";
         $this->medicationRequestModel->write($query, [
            'state' => $newState,
            'id' => $requestID,
         ]);

         echo json_encode(['success' => true, 'message' => 'State updated successfully.']);
      } else {
         echo json_encode(['success' => false, 'error' => 'Invalid input.']);
      }
   }
}
