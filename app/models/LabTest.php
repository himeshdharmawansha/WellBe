<?php

class LabTest extends Model
{

    public function insertRecord($id)
    {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $state = 'Pending';

        $query = "INSERT INTO test_requests (doctor_id, patient_id, date, state)
                  VALUES (?, ?, CURDATE(), ?)";

        $this->query($query, [$doctor_id, $patient_id, $state]);
    }

    public function getPastTestDetails() {}

    public function getLastInsertedId($id)
    {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $date = date('Y-m-d');

        $query = "SELECT id FROM test_requests WHERE doctor_id = ? AND patient_id = ? AND date = ?";
        $result = $this->query($query, [$doctor_id, $patient_id, $date]);

        return $result[0]->id;
    }

    public function insertTest($lab, $id)
    {

        $test_name = $lab['name'];
        $priority = $lab['priority'];
        $lab_id = $id;
        $state = "pending";

        $query = "INSERT INTO test_request_details (test_request_id, test_name, priority, state)
                      Values (?, ?, ?, ?)";

        $this->query($query, [$lab_id, $test_name, $priority, $state]);
    }

    // public function getTest($patient_id)
    // {
    //     $query = "SELECT 
    //                     tr.id,
    //                     d.first_name AS doctor_first_name,
    //                     d.last_name AS doctor_last_name,
    //                     t.date,
    //                     td.start_time,
    //                     tr.state,
    //                     d.specialization,
    //                     trd.test_name,
    //                     trd.priority

    //     FROM test_requests tr
    //     JOIN test_request_details trd ON tr.id = trd.test_request_id
    //      JOIN doctor d ON tr.doctor_id = d.id
    //      JOIN timeslot t ON tr.date = t.slot_id
    //      JOIN timeslot_doctor td ON tr.date = td.slot_id
    //       WHERE patient_id = ?";
    //     $result = $this->query($query, [$patient_id]);
    //     return $result;
    // }

    public function getTest($patient_id)
    {
        $query = "SELECT 
                    tr.id,
                    d.first_name AS doctor_first_name,
                    d.last_name AS doctor_last_name,
                    t.date,
                    td.start_time,
                    trd.state,
                    d.specialization,
                    trd.test_name,
                    trd.priority,
                    trd.file
    FROM test_requests tr
    JOIN test_request_details trd ON tr.id = trd.test_request_id  
    JOIN doctor d ON tr.doctor_id = d.id
    JOIN timeslot t ON tr.date = t.slot_id
    JOIN timeslot_doctor td ON tr.date = td.slot_id
    WHERE patient_id = ?";
        $result = $this->query($query, [$patient_id]);
        return $result;
    }

    public function getRequest($patient_id)
    {
        $query = "SELECT  
                    tr.id,
                    d.first_name AS doctor_first_name,
                    t.date,
                    td.start_time
    FROM test_requests tr
    JOIN test_request_details trd ON tr.id = trd.test_request_id  
    JOIN doctor d ON tr.doctor_id = d.id
    JOIN timeslot t ON tr.date = t.slot_id
    JOIN timeslot_doctor td ON tr.date = td.slot_id
    WHERE patient_id = ?";
        $result = $this->query($query, [$patient_id]);
        return $result;
    }
}
