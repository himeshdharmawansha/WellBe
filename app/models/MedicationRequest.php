<?php

class MedicationRequest extends Model
{
   protected $table = 'medication_requests';
   protected $allowedColumns = ['time', 'date', 'patient_id', 'doctor_id', 'state'];

   public function getAll()
   {
      return $this->read("SELECT mr.*, d.first_name FROM medication_requests mr, doctor d where mr.doctor_id = d.id");
   }

   public function getPendingRequests()
   {
      return $this->read("SELECT * FROM medication_requests WHERE state IN ('pending') ORDER BY id DESC");
   }

   public function getProgressRequests()
   {
      return $this->read("SELECT * FROM medication_requests WHERE state = 'progress' ORDER BY id DESC");
   }

   public function getCompletedRequests()
   {
      return $this->read("SELECT * FROM medication_requests WHERE state = 'completed' ORDER BY id DESC");
   }

   public function searchByPatientId($patientId)
   {
      $query = "SELECT mr.*, d.first_name FROM medication_requests mr, doctor d WHERE d.id = mr.doctor_id AND tr.date = t.slot_id AND patient_id LIKE :patient_id ORDER BY id DESC";
      $params = [':patient_id' => '%' . $patientId . '%'];
      return $this->read($query, $params);
   }

   public function updateRequest($requestID, $remarks, $medications)
   {
      $updateRequestQuery = "UPDATE medication_requests SET state = 'completed', remark = :remarks WHERE id = :requestID";
      $requestParams = [
         ':remarks' => $remarks,
         ':requestID' => $requestID,
      ];
      $this->write($updateRequestQuery, $requestParams);

      foreach ($medications as $medication) {
         $updateDetailQuery = "UPDATE medication_request_details 
                              SET state = :state 
                              WHERE test_request_id = :requestID AND medication_name = :medicationName";
         $detailParams = [
            ':state' => $medication['state'],
            ':requestID' => $requestID,
            ':medicationName' => $medication['medicationName'],
         ];
         $this->write($updateDetailQuery, $detailParams);
      }
   }

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