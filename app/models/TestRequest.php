<?php

class TestRequest extends Model
{
   protected $table = 'test_requests';
   protected $allowedColumns = ['date', 'patient_id', 'doctor_id', 'state'];

   // Fetch all test requests
   public function getAll()
   {
      return $this->read("SELECT * FROM test_requests");
   }

   // Fetch pending test requests
   public function getPendingRequests()
   {
      return $this->read("SELECT * FROM test_requests WHERE state = 'pending'");
   }

   // Fetch ongoing test requests
   public function getOngoingRequests()
   {
      return $this->read("SELECT * FROM test_requests WHERE state = 'ongoing'");
   }

   // Fetch completed test requests
   public function getCompletedRequests()
   {
      return $this->read("SELECT * FROM test_requests WHERE state = 'completed'");
   }

   // Search test requests by patient ID
   public function searchByPatientId($patientId)
   {
      $query = "SELECT * FROM test_requests WHERE patient_id LIKE :patient_id ORDER BY id DESC";
      $params = [':patient_id' => '%' . $patientId . '%'];
      return $this->read($query, $params);
   }

   // Update the state of a test request
   public function updateState($requestID, $newState, $testName)
   {
      $query = "UPDATE test_request_details SET state = :state WHERE req_id = :id AND test_name = :testName";
      $params = [
         'state' => $newState,
         'id' => $requestID,
         'testName' => $testName,
      ];

      $this->write($query, $params);
   }

   // Fetch test details for a specific request
   public function getTestDetails($requestID)
   {
      $query = "SELECT test_name, state, priority, file FROM test_request_details WHERE req_id = :requestID";
      $params = [':requestID' => $requestID];
      return $this->read($query, $params);
   }

   // Update test request details including file uploads
   public function updateRequestDetails($requestID, $tests, $files)
   {
      // Update the test_requests table to mark as completed
      $updateRequestQuery = "UPDATE test_requests SET state = 'completed' WHERE id = :requestID";
      $this->query($updateRequestQuery, [':requestID' => $requestID]);

      // Process each test detail
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

         $query = "UPDATE test_request_details 
                   SET state = :state, file = COALESCE(:file, file)
                   WHERE req_id = :requestID AND test_name = :testName";
         $params = [
            ':state' => $state,
            ':file' => $fileName,
            ':requestID' => $requestID,
            ':testName' => $testName,
         ];
         $this->query($query, $params);
      }
   }

   // Delete a file associated with a test request detail
   public function deleteFile($requestID, $testName)
   {
      // Get the file name
      $query = "SELECT file FROM test_request_details WHERE req_id = :requestID AND test_name = :testName";
      $params = [':requestID' => $requestID, ':testName' => $testName];
      $result = $this->read($query, $params);

      if (!empty($result[0]['file'])) {
         $filePath = $_SERVER['DOCUMENT_ROOT'] . '/WellBe/public/assets/files/' . $result[0]['file'];

         // Delete the file from the server
         if (file_exists($filePath)) {
            unlink($filePath);
         }

         // Update database to set file to NULL
         $updateQuery = "UPDATE test_request_details SET file = NULL WHERE req_id = :requestID AND test_name = :testName";
         $this->read($updateQuery, $params);
      }
   }

   // Get the URL of a file associated with a test request detail
   public function getFileUrl($requestID, $testName)
   {
      $query = "SELECT file FROM test_request_details WHERE req_id = :requestID AND test_name = :testName";
      $params = [':requestID' => $requestID, ':testName' => $testName];
      $result = $this->read($query, $params);

      if (!empty($result[0]['file'])) {
         return ROOT . '/assets/files/' . $result[0]['file'];
      }
      return null;
   }
}