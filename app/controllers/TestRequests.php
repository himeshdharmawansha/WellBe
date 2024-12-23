<?php

class TestRequests extends Controller
{
   protected $testRequestModel;

   public function __construct()
   {
      // Initialize the testRequest model
      $this->testRequestModel = new TestRequest();
   }

   // Fetch all test requests and pass them to the view
   public function index()
   {
      $pendingRequests = $this->testRequestModel->read("SELECT * FROM test_requests WHERE state = 'pending'");
      $ongoingRequests = $this->testRequestModel->read("SELECT * FROM test_requests WHERE state = 'ongoing'");
      $completedRequests = $this->testRequestModel->read("SELECT * FROM test_requests WHERE state = 'completed'");

      $this->view('Lab/requests', 'requests', [
         'pendingRequests' => $pendingRequests,
         'ongoingRequests' => $ongoingRequests,
         'completedRequests' => $completedRequests,
      ]);
   }

   // API endpoint to retrieve requests as JSON for AJAX
   public function getRequestsJson()
   {
      $requests = $this->testRequestModel->getAll();
      header('Content-Type: application/json');
      echo json_encode($requests);
      exit;
   }

   // API endpoint to search test requests by Patient ID
   public function searchRequestsByPatientId()
   {
      $searchTerm = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : '';

      if (!empty($searchTerm)) {
         // Search for test requests based on the patient_id
         $query = "SELECT * FROM test_requests WHERE patient_id LIKE :patient_id ORDER BY id DESC";
         $params = [
            ':patient_id' => '%' . $searchTerm . '%',
         ];

         $requests = $this->testRequestModel->read($query, $params);

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
         $tests = $decodedData['tests'] ?? [];

         if ($requestID) {
            $db = new Database();

            // Update the `test_requests` table to mark the state as "completed"
            $updateRequestQuery = "UPDATE test_requests SET state = 'completed', remark = :remarks WHERE id = :requestID";
            $requestParams = [
               ':remarks' => $remarks,
               ':requestID' => $requestID,
            ];
            $db->write($updateRequestQuery, $requestParams);

            // Update the `test_request_details` table for each test
            foreach ($tests as $test) {
               $updateDetailQuery = "UPDATE test_request_details 
                                        SET state = :state 
                                        WHERE req_id = :requestID AND test_name = :testName";
               $detailParams = [
                  ':state' => $test['state'],
                  ':requestID' => $requestID,
                  ':testName' => $test['testName'],
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

         $query = "UPDATE test_requests SET state = :state WHERE id = :id";
         $this->testRequestModel->write($query, [
            'state' => $newState,
            'id' => $requestID,
         ]);

         echo json_encode(['success' => true, 'message' => 'State updated successfully.']);
      } else {
         echo json_encode(['success' => false, 'error' => 'Invalid input.']);
      }
   }

   public function getTestDetails($requestID)
   {
      $db = new Database();
      $query = "SELECT test_name, state, priority, file FROM test_request_details WHERE req_id = :requestID";
      $params = [':requestID' => $requestID];
      return $db->read($query, $params);
   }
}
