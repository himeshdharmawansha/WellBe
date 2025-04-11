<?php

class TestRequests extends Controller
{
   protected $testRequestModel;

   public function __construct()
   {
      $this->testRequestModel = new TestRequest();
   }

   public function index()
   {

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
      $searchTerm = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : '';

      if (!empty($searchTerm)) {
         $requests = $this->testRequestModel->searchByPatientId($searchTerm);
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
         $testName = htmlspecialchars($data['testName']);
         $this->testRequestModel->updateState($requestID, $newState, $testName);
         echo json_encode(['success' => true, 'message' => 'State updated successfully.']);
      } else {
         echo json_encode(['success' => false, 'error' => 'Invalid input.']);
      }
   }

   public function getTestDetails($requestID)
   {
      return $this->testRequestModel->getTestDetails($requestID);
   }

   public function updateRequestDetails()
   {
      $response = ['success' => false];

      try {
         $data = $_POST;
         $files = $_FILES;

         if (!isset($data['tests']) || empty($data['tests'])) {
            throw new Exception("No test details provided.");
         }

         $tests = json_decode($data['tests'], true);
         $requestID = $data['requestID'];

         $this->testRequestModel->updateRequestDetails($requestID, $tests, $files);
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

         $this->testRequestModel->deleteFile($requestID, $testName);
         $response['success'] = true;
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

         $fileUrl = $this->testRequestModel->getFileUrl($requestID, $testName);
         if ($fileUrl) {
            $response['fileUrl'] = $fileUrl;
            $response['success'] = true;
         }
      }

      echo json_encode($response);
   }
}