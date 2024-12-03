<?php

class LabTest extends Model {

    public function insertRecord($id) {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $state = 'Pending';

        $query = "INSERT INTO test_requests (doctor_id, patient_id, date, state)
                  VALUES (?, ?, CURDATE(), ?)";

        $this->query($query, [$doctor_id, $patient_id, $state]);

    }

    public function getLastInsertedId($id) {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $date = date('Y-m-d');

        $query = "SELECT id FROM test_requests WHERE doctor_id = ? AND patient_id = ? AND date = ?";
        $result = $this->query($query, [$doctor_id, $patient_id, $date]); // Assuming `query()` returns the result set
        
        return $result[0]->id;
    }

    public function insertTest($lab,$id){

        $test_name = $lab['name'];
        $priority = $lab['priority'];
        $lab_id = $id;
        $state = "pending";

            $query = "INSERT INTO test_request_details (test_request_id, test_name, priority, state)
                      Values (?, ?, ?, ?)";

            $this->query($query, [$lab_id, $test_name, $priority, $state]);
    }

}