<?php
class Appointments extends Model
{
    protected $table = 'appointment';

    protected $allowedColumns = [

        'id',
        'appointment_id',
        'doctor_id',
        'patient_id',
        'date',
        'payment_fee',
        'payment_status',
        'state',
        'patient_type',
        'scheduled',

    ];


    public function getTodayAppointments($date = '')
    {

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

        $data =  $this->query($query, [$doctor, $day]);
        return $data;
    }

    public function makeNewAppointment($data)
    {
        // Extract fields from $data safely
        $doctorId = $data['docId'] ?? null;
        $specialization = $data['specialization'] ?? null;
        $patientId = $data['patientId'] ?? null;
        $date = $data['dateId'] ?? null;
        $appointment_time = $data['appointment_time'] ?? null;
        $appointment_number = $data['appointment_number'] ?? null;
        $appointment_fee = $data['appointment_fee'] ?? null;
        $contact_number = $data['contact_number'] ?? null;
        $patientType = $data['patient_type'] ?? null;

        if (!$doctorId || !$date || !$patientId) {
            return false; // Basic validation
        }

        $query = "INSERT INTO appointment (
            appointment_id,
            doctor_id,
            patient_id,
            date,
            payment_fee,
            state,
            patient_type,
            scheduled
        ) VALUES (
            :appointment_id,
            :doctor_id,
            :patient_id,
            :date,
            :appointment_fee,
            :state,
            :patient_type,
            :scheduled
        )";

        $params = [
            'appointment_id' => $appointment_number,
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'date' => $date,
            'appointment_fee' => $appointment_fee,
            'state' => "NOT PRESENT",
            'patient_type' => $patientType,
            'scheduled' => "SCHEDULED"
        ];

        // Assuming you have a query method like in your `getAppointment` example
        return $this->query($query, $params);
    }

    public function checkAppointmentExists($data)
    {
        $doctorId = $data['docId'] ?? null;
        $patientId = $data['patientId'] ?? null;
        $date = $data['dateId'] ?? null;
        $appointment_number = $data['appointment_number'] ?? null;
        $query = "SELECT * FROM appointment WHERE appointment_id = ? AND doctor_id = ? AND patient_id = ? AND date = ?;";
        $data = $this->query($query, [$appointment_number, $doctorId, $patientId, $date]);

        return !empty($data); // Return true if exists, false otherwise
    }

    public function getPatientDetails($id)
    {
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

    public function getAppointmentCount(){

        $query = "SELECT d.date, COUNT(a.id) AS appointment_count
                    FROM (
                        SELECT CURDATE() AS date
                        UNION ALL SELECT CURDATE() - INTERVAL 1 DAY
                        UNION ALL SELECT CURDATE() - INTERVAL 2 DAY
                        UNION ALL SELECT CURDATE() - INTERVAL 3 DAY
                        UNION ALL SELECT CURDATE() - INTERVAL 4 DAY
                        UNION ALL SELECT CURDATE() - INTERVAL 5 DAY
                        UNION ALL SELECT CURDATE() - INTERVAL 6 DAY
                    ) d
                    LEFT JOIN timeslot t ON t.date = d.date
                    LEFT JOIN appointment a ON a.date = t.slot_id AND a.doctor_id = ?
                    GROUP BY d.date
                    ORDER BY d.date ASC;";

        $appointmentCount = $this->query($query,[$_SESSION['USER']->id]);
        return $appointmentCount;
    }


    public function endAppointment($id)
    {

        //echo "updated";

        $app_id = $id;
        //echo $app_id;

        $query = "UPDATE appointment
                  SET state = 'DONE'
                  WHERE appointment_id = ?;";

        $this->query($query, [$app_id]);
    }

    public function getAppointment($id, $today_id)
    {


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

        $data = ['doctor_id' => $id, 'date' => $today_id];

        $result = $this->query($query, $data);

        //print_r($result);
        return $result;
    }

    public function decWalletAmount()
    {

        $query = "UPDATE patient SET e_wallet = e_wallet - 1500 WHERE id = ?";
        $this->query($query, [$_SESSION['USER']->id]);
    }


    public function saveAppointmentDetails()
    {

        $doctor_id = $_SESSION['USER']->id;
        $patient_id = $_POST['patient'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $fees = $_POST['fees'];
        $appointment_no = $_POST['appointment_no'];

        $query = "INSERT INTO appointment(doctor_id,patient_id,date,time,state) VALUES(?,?,?,?,?)";
        $query = "INSERT INTO appointment(doctor_id,patient_id,date,time,state) VALUES(?,?,?,?,?)";

        $this->query($query, [$doctor_id, $patient_id, $date, $time, $fees]);


        return true;
    }


    public function rescheduleAppointment($id, $newDocId,$newAppointId,$newDateId){

        $query = "UPDATE appointment SET doctor_id = ?, appointment_id = ?, date = ?, scheduled = 'Scheduled' WHERE id = ?;";

        $this->query($query,[$newDocId, $newAppointId, $newDateId, $id]);
    }

    public function getRescheduledApppointments($patientId){

        $query = "SELECT a.id , CONCAT(d.first_name , ' ' , d.last_name) as doctor_name,d.specialization,t.date  FROM appointment a
            JOIN doctor d ON a.doctor_id = d.id
            JOIN timeslot t ON a.date = t.slot_id
            WHERE a.patient_id = $patientId AND a.scheduled = 'Rescheduled';";
        $rescheduledAppointments = $this->query($query);

        return $rescheduledAppointments;
    }

    public function deleteAppointment($appointmentId){

        $query = "DELETE FROM appointment WHERE id = ? ;";
        $this->query($query,[$appointmentId]);

        header("Location: http://localhost/wellbe/public/patient/");
    }

    public function updatePaymentStatus($appointment_id, $doctor_id, $patient_id, $date, $status)
    {

        $query = "UPDATE appointment SET payment_status = ? WHERE appointment_id = ? AND doctor_id = ? AND patient_id = ? AND date = ?";


        $this->query($query, [$status, $appointment_id, $doctor_id, $patient_id, $date]);

        return true;
    }

    public function getAllAppointmentsForPatient($patient_id)
    {
        $query = "
        SELECT 
            a.id,
            a.patient_id,
            a.doctor_id,
            a.appointment_id,
            a.state,
            t.date,
            a.payment_status,
            d.first_name AS doctor_first_name,
            d.last_name AS doctor_last_name,
            d.specialization,
            td.start_time
            
        FROM 
            appointment a
        JOIN 
            doctor d ON a.doctor_id = d.id
        JOIN 
            patient p ON a.patient_id = p.id
        JOIN
            timeslot t ON a.date = t.slot_id
        JOIN
            timeslot_doctor td ON a.date = td.slot_id
        WHERE 
            a.patient_id = ?
        ORDER BY 
            a.date ASC
    ";
        return $this->query($query, [$patient_id]);
    }

}
