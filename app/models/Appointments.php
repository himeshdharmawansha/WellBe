<?php

class Appointments extends Model{

    public function getTodayAppointments(){

    $doctor = $_SESSION['USER']->id;
    $date = date('Y-m-d');

    $query = "SELECT 
        appointment.appointment_id,
        appointment.doctor_id,
        appointment.patient_id,
        appointment.date,
        appointment.state,
        patient.id,
        patient.nic,
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
            AND appointment.date = ?";

    $data =  $this->query($query,[$doctor, $date]);
    return $data;

    }

    public function getPatientDetails($id){

        $appointment_id = $id;

        $query=    "SELECT 
                    patient.*
                    FROM 
                    patient
                    JOIN 
                    appointment
                    ON 
                    appointment.patient_id = patient.id
                    WHERE 
                    appointment.appointment_id = ?;";
                    
        $data = $this->query($query,[$appointment_id]);

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

public function saveAppointmentDetails(){

    $doctor_id = $_SESSION['USER']->id;
    $patient_id = $_POST['patient'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $fees = $_POST['fees'];
    $appointment_no = $_POST['appointment_no'];

    $query = "INSERT INTO appointment(doctor_id,patient_id,date,time,state) VALUES(?,?,?,?,?)";

    $this->query($query,[$doctor_id,$patient_id,$date,$time,$fees]);

    return true;
}



}

?>