<?php

use LDAP\Result;

class TestRequest extends Model
{
   protected $table = 'test_requests';
   protected $allowedColumns = ['date', 'patient_id', 'doctor_id', 'state'];

   
   public function getAll()
   {
      return $this->read("SELECT tr.*, t.date as date_t,d.first_name FROM test_requests tr, timeslot t, doctor d where tr.date = t.slot_id AND d.id = tr.doctor_id");
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
      $query = "SELECT tr.*, tr.patient_id, d.first_name, t.date as date_t FROM test_requests tr, doctor d, timeslot t WHERE d.id = tr.doctor_id AND tr.date = t.slot_id AND patient_id LIKE :patient_id ORDER BY id DESC";
      $params = [':patient_id' => '%' . $patientId . '%'];
      return $this->read($query, $params);
   }

   public function getPatientDetails($patientID) {
      $query = "SELECT first_name, email FROM patient WHERE id = :patientID";
      $params = [':patientID' => $patientID];
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

   public function updateRequestDetails($requestID, $tests, $files, $patientID)
   {
      // Update request state
      $updateRequestQuery = "UPDATE test_requests SET state = 'completed' WHERE id = :requestID";
      $this->query($updateRequestQuery, [':requestID' => $requestID]);

      $query = "SELECT date FROM test_requests WHERE id = :requestID";
      $params = [':requestID' => $requestID];
      $result = $this->read($query, $params);

      if (empty($result) || !isset($result[0]['date'])) {
         throw new Exception("Invalid request ID or missing date for request ID: $requestID");
      }

      $slot_id = $result[0]['date'];

      foreach ($tests as $test) {
         $testName = $test['testName'] ?? null;
         $state = $test['state'] ?? null;

         if (empty($testName) || empty($state)) {
            throw new Exception("Invalid test name or state for test: " . ($testName ?? 'unknown'));
         }

         $fileName = null;
         if (isset($files[$testName]) && $files[$testName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/WellBe/public/assets/files/';
            $originalExtension = pathinfo($files[$testName]['name'], PATHINFO_EXTENSION);
            $fileName = "{$slot_id}_{$patientID}_{$requestID}_{$testName}.{$originalExtension}";
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

   public function getPastTestDetials($patient_id) {
      $query = "SELECT 
                  tr.id AS request_id,
                  CONCAT(d.first_name, ' ', d.last_name) AS doctor,
                  t.date,
                  CONCAT('[', GROUP_CONCAT(
                      JSON_OBJECT(
                          'test_name', IFNULL(trd.test_name, ''),
                          'priority', IFNULL(trd.priority, ''),
                          'file', IFNULL(trd.file, '')
                      )
                  ), ']') AS tests
              FROM test_requests tr
              JOIN doctor d ON tr.doctor_id = d.id
              JOIN timeslot t ON tr.date = t.slot_id
              JOIN test_request_details trd ON tr.id = trd.test_request_id
              WHERE tr.patient_id = ?
              GROUP BY tr.id, d.first_name, d.last_name, t.date
              ORDER BY tr.id ASC;";
  
      $records = $this->query($query, [$patient_id]);
      return $records;
  }
}
