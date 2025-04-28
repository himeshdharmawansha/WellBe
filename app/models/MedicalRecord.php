<?php

class MedicalRecord extends Model
{

    public function insertRecord($remarks, $data, $id, $file_name, $appointment_id)
    {

        $timeslot = new Timeslot();
        $today = $timeslot -> getDateId(date('y-m-d'));

        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $state = "new";
        $diagnosis = $data;

        $query = "INSERT INTO medication_requests (doctor_id, patient_id, date, time, remark, state, diagnosis, file_name, appointment_id)
                  VALUES (?, ?, ?, CURTIME(), ?, ?, ?, ?, ?)";

        $this->query($query, [$doctor_id, $patient_id, $today[0]->slot_id, $remarks, $state, $diagnosis, $file_name, $appointment_id]);
    }

    public function getPastRecordsDetials($patient_id)
    {
        $query = "SELECT 
                    mr.id AS request_id,
                    CONCAT(d.first_name, ' ', d.last_name) AS doctor,
                    t.date,
                    mr.diagnosis,
                    mr.file_name,
                    mr.remark,
                    CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'medication_name', IFNULL(mrd.medication_name, ''),
                            'dosage', IFNULL(mrd.dosage, ''),
                            'taken_time', IFNULL(mrd.taken_time, ''),
                            'substitution', IFNULL(mrd.substitution, '')
                        )
                    ), ']') AS medications
                FROM medication_requests mr
                JOIN doctor d ON mr.doctor_id = d.id
                JOIN timeslot t ON mr.date = t.slot_id
                JOIN medication_request_details mrd ON mr.id = mrd.req_id
                WHERE mr.patient_id = ?
                GROUP BY mr.id, d.first_name, d.last_name, t.date, mr.diagnosis
                ORDER BY mr.id ASC;";

        $records = $this->query($query, [$patient_id]);
        return $records;
    }

    public function getPatientType()
    {

        $patientId = $_SESSION['USER']->id;

        $query = "SELECT * FROM medication_requests WHERE patient_id = :patient_id;";

        $data = ['patient_id' => $patientId];

        $pastRecords = $this->query($query, $data);
        return $pastRecords;
    }


    public function getPastRecords($patient_id)
    {

        //echo $patient_id;
        $doctor_id = $_SESSION['USER']->id;
        //echo $doctor_id;

        $query = "SELECT 
                    mr.id AS request_id,
                    mr.doctor_id,
                    mr.patient_id,
                    mr.date,
                    mr.diagnosis,
                    mrd.medication_name,
                    mrd.dosage,
                    mrd.taken_time,
                    mrd.substitution
                FROM medication_requests mr
                JOIN medication_request_details mrd ON mr.id = mrd.req_id
                WHERE mr.patient_id = ? AND mr.doctor_id = ?;";

        $records = $this->query($query, [$patient_id, $doctor_id]);

        $groupedData = [];

        // Process the data to group by request_id
        foreach ($records as $row) {
            $request_id = $row->request_id;

            // If this request_id is not in the grouped data, initialize it
            if (!isset($groupedData[$request_id])) {
                $groupedData[$request_id] = [
                    'request_id' => $request_id,
                    'doctor_id' => $row->doctor_id,
                    'patient_id' => $row->patient_id,
                    'date' => $row->date,
                    'diagnosis' => $row->diagnosis,
                    'medications' => []  // Store medications as an array
                ];
            }

            // Add the medication details to the request
            $groupedData[$request_id]['medications'][] = [
                'medication_name' => $row->medication_name,
                'dosage' => $row->dosage,
                'taken_time' => $row->taken_time,
                'substitution' => $row->substitution
            ];
        }

        // Convert associative array to indexed array
        return array_values($groupedData);
    }

    public function getLastInsertedId($id, $appointment_id)
    {
        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $id;
        $timeslot = new Timeslot();
        $date = $timeslot -> getDateId(date('y-m-d'));

        $query = "SELECT id FROM medication_requests WHERE doctor_id = ? AND patient_id = ? AND date = ? AND appointment_id = ?";
        $result = $this->query($query, [$doctor_id, $patient_id, $date[0]->slot_id,  $appointment_id]);

        return $result[0]->id;
    }

    public function insertMed($med, $id)
    {

        $howToTake = "{$med['morning']} {$med['noon']} {$med['night']} {$med['if_needed']}";
        $medication = $med['name'];
        $dosage = $med['dosage'];
        $susbstitution = $med['do_not_substitute'];
        $med_id = $id;

        $query = "INSERT INTO medication_request_details (req_id, medication_name, dosage, taken_time, substitution)
                      Values (?, ?, ?, ?, ?)";

        $this->query($query, [$med_id, $medication, $dosage, $howToTake, $susbstitution]);
    }

    public function getLabTestData($testReqId){

    }

    public function getMedicalRecordData($testReqId){
        $query = "SELECT mrd.*, t.date, CONCAT(d.first_name, ' ', d.last_name) AS doctor_name , mr.diagnosis, mr.file_name, mr.remark
          FROM medication_request_details mrd
          JOIN medication_requests mr ON mr.id = mrd.req_id
          JOIN doctor d ON d.id = mr.doctor_id
          JOIN timeslot t ON t.slot_id = mr.date
          WHERE mr.id = ?;";

        $result = $this->query($query, [$testReqId]);
        return $result;
    }

    public function getRequest($patient_id)
    {
        $query = "SELECT 
                        mr.id,
                        d.first_name AS doctor_first_name,
                        d.last_name AS doctor_last_name,
                        t.date,
                        td.start_time,
                        mr.diagnosis,
                        d.specialization

        
         FROM medication_requests mr
        JOIN doctor d ON mr.doctor_id = d.id
        JOIN timeslot t ON mr.date = t.slot_id
        JOIN timeslot_doctor td ON mr.date = td.slot_id
         
         
         
         WHERE patient_id = ?";
        $result = $this->query($query, [$patient_id]);
        return $result;
    }

    public function getMed($req_id)
    {
        $query = "SELECT
                        mrd.medication_name,
                        mrd.dosage,
                        mrd.taken_time,
                        mrd.substitution,
                        mr.remark,
                        mr.state,
                        d.first_name AS doctor_first_name,
                        d.last_name AS doctor_last_name,
                        t.date,
                        mr.diagnosis
        
        FROM medication_request_details mrd
        JOIN medication_requests mr ON mrd.req_id = mr.id
        JOIN doctor d ON mr.doctor_id = d.id   
        LEFT JOIN timeslot t ON mr.date = t.slot_id
        WHERE mrd.req_id = ?";
        $result = $this->query($query, [$req_id]);
        return $result;
    }
}
