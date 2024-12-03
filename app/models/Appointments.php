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
}

?>