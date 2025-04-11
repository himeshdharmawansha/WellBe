<?php

class TestRequest extends Model
{
   protected $table = 'test_requests';
   protected $allowedColumns = ['date', 'patient_id', 'doctor_id', 'state'];

   public function getAll()
   {
      return $this->read("SELECT * FROM test_requests");
   }

   public function getPendingRequests()
   {
      return $this->read("SELECT * FROM test_requests WHERE state = 'pending'");
   }

   public function getOngoingRequests()
   {
      return $this->read("SELECT * FROM test_requests WHERE state = 'ongoing'");
   }

   public function getCompletedRequests()
   {
      return $this->read("SELECT * FROM test_requests WHERE state = 'completed'");
   }

   public function searchByPatientId($patientId)
   {
      $query = "SELECT * FROM test_requests WHERE patient_id LIKE :patient_id ORDER BY id DESC";
      $params = [':patient_id' => '%' . $patientId . '%'];
      return $this->read($query, $params);
   }

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

   public function getTestDetails($requestID)
   {
      $query = "SELECT test_name, state, priority, file FROM test_request_details WHERE req_id = :requestID";
      $params = [':requestID' => $requestID];
      return $this->read($query, $params);
   }

   public function updateRequestDetails($requestID, $tests, $files)
   {
      $updateRequestQuery = "UPDATE test_requests SET state = 'completed' WHERE id = :requestID";
      $this->query($updateRequestQuery, [':requestID' => $requestID]);

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

   public function deleteFile($requestID, $testName)
   {
      $query = "SELECT file FROM test_request_details WHERE req_id = :requestID AND test_name = :testName";
      $params = [':requestID' => $requestID, ':testName' => $testName];
      $result = $this->read($query, $params);

      if (!empty($result[0]['file'])) {
         $filePath = $_SERVER['DOCUMENT_ROOT'] . '/WellBe/public/assets/files/' . $result[0]['file'];

         if (file_exists($filePath)) {
            unlink($filePath);
         }

         $updateQuery = "UPDATE test_request_details SET file = NULL WHERE req_id = :requestID AND test_name = :testName";
         $this->read($updateQuery, $params);
      }
   }

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