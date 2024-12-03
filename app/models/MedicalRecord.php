<?php

class MedicalRecord extends Model {

    public function insertRecord($remarks, $data, $id) {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $state = "new";
        $diagnosis = $data;

        $query = "INSERT INTO medication_requests (doctor_id, patient_id, date, time, remark, state, diagnosis)
                  VALUES (?, ?, CURDATE(), CURTIME(), ?, ?, ?)";

        $this->query($query, [$doctor_id, $patient_id, $remarks, $state, $diagnosis]);

    }

    public function getLastInsertedId($id) {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $date = date('Y-m-d');

        $query = "SELECT id FROM medication_requests WHERE doctor_id = ? AND patient_id = ? AND date = ?";
        $result = $this->query($query, [$doctor_id, $patient_id, $date]); // Assuming `query()` returns the result set
        
        return $result[0]->id;
    }

    public function insertMed($med,$id){

        $howToTake = "{$med['morning']} {$med['noon']} {$med['night']} {$med['if_needed']}";
        $medication = $med['name'];
        $dosage = $med['dosage'];
        $susbstitution = $med['do_not_substitute'];
        $med_id = $id;

            $query = "INSERT INTO medication_request_details (req_id, medication_name, dosage, taken_time, substitution)
                      Values (?, ?, ?, ?, ?)";

            $this->query($query, [$med_id, $medication, $dosage, $howToTake, $susbstitution]);
    }

}