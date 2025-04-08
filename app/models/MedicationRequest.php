<?php

class MedicationRequest extends Model
{
   protected $table = 'medication_requests';
   protected $allowedColumns = ['time', 'date', 'patient_id', 'doctor_id', 'state'];

   // Fetch all medication requests
   public function getAll()
   {
      return $this->read("SELECT * FROM medication_requests");
   }

   // Fetch pending medication requests
   public function getPendingRequests()
   {
      return $this->read("SELECT * FROM medication_requests WHERE state = 'pending'");
   }

   // Fetch in-progress medication requests
   public function getProgressRequests()
   {
      return $this->read("SELECT * FROM medication_requests WHERE state = 'progress'");
   }

   // Fetch completed medication requests
   public function getCompletedRequests()
   {
      return $this->read("SELECT * FROM medication_requests WHERE state = 'completed'");
   }

   // Search medication requests by patient ID
   public function searchByPatientId($patientId)
   {
      $query = "SELECT * FROM medication_requests WHERE patient_id LIKE :patient_id ORDER BY id DESC";
      $params = [':patient_id' => '%' . $patientId . '%'];
      return $this->read($query, $params);
   }

   // Update a medication request and its details
   public function updateRequest($requestID, $remarks, $medications)
   {
      // Update the medication_requests table
      $updateRequestQuery = "UPDATE medication_requests SET state = 'completed', remark = :remarks WHERE id = :requestID";
      $requestParams = [
         ':remarks' => $remarks,
         ':requestID' => $requestID,
      ];
      $this->write($updateRequestQuery, $requestParams);

      // Update the medication_request_details table for each medication
      foreach ($medications as $medication) {
         $updateDetailQuery = "UPDATE medication_request_details 
                              SET state = :state 
                              WHERE req_id = :requestID AND medication_name = :medicationName";
         $detailParams = [
            ':state' => $medication['state'],
            ':requestID' => $requestID,
            ':medicationName' => $medication['medicationName'],
         ];
         $this->write($updateDetailQuery, $detailParams);
      }
   }

   // Update the state of a medication request
   public function updateState($requestID, $newState)
   {
      $query = "UPDATE medication_requests SET state = :state WHERE id = :id";
      $params = [
         'state' => $newState,
         'id' => $requestID,
      ];
      $this->write($query, $params);
   }
}