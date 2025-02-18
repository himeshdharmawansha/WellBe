<?php
class Appointments extends Model{

    public function getTodayAppointments($date = ''){

    $day = empty($date) ? date('Y-m-d') : $date;
    $doctor = $_SESSION['USER']->id;

    $query = "SELECT 
        appointment.appointment_id,
        appointment.doctor_id,
        appointment.patient_id,
        appointment.date,
        appointment.state,
        appointment.patient_type,
        patient.id,
        patient.gender,
        patient.first_name,
        patient.last_name
        FROM 
            appointment
        JOIN 
            patient
        ON 
            appointment.patient_id = patient.id
        WHERE 
            appointment.doctor_id = ? 
            AND appointment.date = (
                SELECT slot_id FROM timeslot WHERE date = ?
            );";

    $data =  $this->query($query,[$doctor, $day]);
    return $data;

    }


    public function getPatientDetails($id) {
        $appointment_id = $id;
        $doc_id = $_SESSION['USER']->id;
        
        $query = "SELECT 
                    patient.* 
                  FROM 
                    patient
                  JOIN 
                    appointment 
                  ON 
                    appointment.patient_id = patient.id
                  WHERE 
                    appointment.appointment_id = ?
                    AND appointment.doctor_id = ?
                    AND appointment.date = (
                        SELECT slot_id FROM timeslot WHERE date = ?
                    );";
        
        $data = $this->query($query, [$appointment_id, $doc_id, date('Y-m-d')]);
        
        return $data;
    }
    

    public function endAppointment($id){

        //echo "updated";

        $app_id = $id;
        //echo $app_id;

        $query = "UPDATE appointment
                  SET state = 'DONE'
                  WHERE appointment_id = ?;";

        $this->query($query,[$app_id]);
    }

    public function getAppointment($id,$today_id){

        
        $query = "SELECT a.*
          FROM appointment a
          JOIN (
              SELECT date, MAX(appointment_id) AS max_appointment_id
              FROM appointment
              WHERE doctor_id = :doctor_id AND date > :date
              GROUP BY date
              ORDER BY date ASC
          ) max_appt 
          ON a.date = max_appt.date 
          AND a.appointment_id = max_appt.max_appointment_id
          WHERE a.doctor_id = :doctor_id
          ORDER By a.date ASC";

        $data = ['doctor_id' => $id,'date'=>$today_id];

        $result = $this->query($query, $data);

        //print_r($result);
        return $result;
    }
}

?>