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
   public function updateRequestDetails()
   {
      $response = ['success' => false];
      $db = new Database();

      try {
         $data = $_POST;
         $files = $_FILES;

         if (!isset($data['tests']) || empty($data['tests'])) {
            throw new Exception("No test details provided.");
         }

         $tests = json_decode($data['tests'], true);

         foreach ($tests as $test) {
            $testName = $test['testName'];
            $state = $test['state'];
            $fileName = null;

            if (isset($files[$testName]) && $files[$testName]['error'] === UPLOAD_ERR_OK) {
               $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/WellBe/public/assets/files/';
               $fileName = basename($files[$testName]['name']);
               $uploadPath = $uploadDir . $fileName;

               if (!file_exists($uploadDir)) {
                  mkdir($uploadDir, 0777, true);
               }

               if (!move_uploaded_file($files[$testName]['tmp_name'], $uploadPath)) {
                  throw new Exception("Failed to upload file for test: $testName");
               }
            }

            $updateRequestQuery = "UPDATE test_requests SET state = 'completed' WHERE id = :requestID";
            $requestParams = [
               ':requestID' => $data['requestID'],
            ];
            $db->query($updateRequestQuery, $requestParams);

            $query = "UPDATE test_request_details 
                      SET state = :state, file = COALESCE(:file, file)
                      WHERE req_id = :requestID AND test_name = :testName";

            $params = [
               ':state' => $state,
               ':file' => $fileName,
               ':requestID' => $data['requestID'], // Ensure `requestID` is sent from the client
               ':testName' => $testName,
            ];

            $db->query($query, $params);
         }

         $response['success'] = true;
      } catch (Exception $e) {
         $response['error'] = $e->getMessage();
      }

      echo json_encode($response);
      exit;
   }

   public function deleteFile()
   {
      $response = ['success' => false];
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['requestID'], $data['testName'])) {
         $requestID = $data['requestID'];
         $testName = $data['testName'];

         $db = new Database();

         // Get the file name
         $query = "SELECT file FROM test_request_details WHERE req_id = :requestID AND test_name = :testName";
         $params = [':requestID' => $requestID, ':testName' => $testName];
         $result = $db->read($query, $params);

         if (!empty($result[0]['file'])) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/WellBe/public/assets/files/' . $result[0]['file'];

            // Delete the file
            if (file_exists($filePath)) {
               unlink($filePath);
            }

            // Update database to set file to NULL
            $updateQuery = "UPDATE test_request_details SET file = NULL WHERE req_id = :requestID AND test_name = :testName";
            $db->read($updateQuery, $params);

            $response['success'] = true;
         }
      }

      echo json_encode($response);
   }

   public function getFileUrl()
   {
      $response = ['success' => false];
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['requestID'], $data['testName'])) {
         $requestID = $data['requestID'];
         $testName = $data['testName'];

         $db = new Database();

         // Get the file name
         $query = "SELECT file FROM test_request_details WHERE req_id = :requestID AND test_name = :testName";
         $params = [':requestID' => $requestID, ':testName' => $testName];
         $result = $db->read($query, $params);

         if (!empty($result[0]['file'])) {
            $response['fileUrl'] = ROOT . '/assets/files/' . $result[0]['file'];
            $response['success'] = true;
         }
      }

      echo json_encode($response);
   }
}
