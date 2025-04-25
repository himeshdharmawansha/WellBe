<?php

//user class

class Timeslot extends Model
{
    public function createTimeslot(){

        $today = date('Y-m-d');
        // print_r($today);

        for ($i = 0; $i < 15; $i++) {

            $exist = [];

            $currentDate = date('Y-m-d', strtotime("+$i days"));
    
            $query = "SELECT COUNT(*) AS count FROM timeslot WHERE date = ?";
            $exist = $this->query($query, [$currentDate]);
            //print_r($exist);

            if (!isset($exist[0])) {
                print_r($exist);
                $sql_insert = "INSERT INTO timeslot (date) VALUES (?)";
                $this->query($sql_insert, [$currentDate]);
            }else{
        
                $count = $exist[0]->count;
                if($count == 0){
                    //echo $count;
                    $sql_insert = "INSERT INTO timeslot (date) VALUES (?)";
                    $this->query($sql_insert, [$currentDate]);
                }
            }
        }
    }

    public function getSchedule(){

        /*$query = "SELECT date,
        JSON_EXTRACT(doctor_timeslot, '$.{$_SESSION['USER']->id}') AS timeslots
        FROM timeslot
        WHERE JSON_CONTAINS_PATH(doctor_timeslot, 'one', '$.{$_SESSION['USER']->id}');";*/

        $query = "
                    SELECT 
                        t.date,
                        td.start_time AS start,
                        td.end_time AS end,
                        td.session AS session
                    FROM 
                        timeslot t
                    JOIN 
                        timeslot_doctor td ON t.slot_id = td.slot_id
                    JOIN 
                        doctor d ON td.doctor_id = d.id
                    WHERE 
                        d.id = ?
                    ORDER BY 
                        t.date, td.start_time;";


		$schedule =  $this->query($query,[$_SESSION['USER']->id]);

        //echo "<pre>";  // Optional: Format output for readability
		//print_r($schedule);  // Displays array structure
		//echo "</pre>";

        return $schedule;
    }

    public function updateSchedule($date,$timeslot){

        $id = $_SESSION['USER']->id;
        $timeSlots = explode(',', $timeslot);
        
        $query = "INSERT INTO timeslot_doctor (slot_id, doctor_id, start_time, end_time, session)
                VALUES (
                    (SELECT slot_id FROM timeslot WHERE date = ?), 
                    ?, 
                    ?, 
                    ?,
                    ?
                );";

        $this->query($query, [$date, $id, $timeSlots[0], $timeSlots[1],"SET"]);
        redirect("doctor");
    }

    public function deleteDate($date){

        $query = "DELETE td FROM timeslot_doctor td
                JOIN timeslot ts ON td.slot_id = ts.slot_id
                WHERE ts.date = ? 
                AND td.doctor_id = ?;";

        $this->query($query,[$date,$_SESSION['USER']->id]);
        

        $query = "UPDATE appointment a
                JOIN timeslot ts ON a.date = ts.slot_id
                SET a.scheduled = 'Rescheduled'
                WHERE ts.date = ? 
                AND a.doctor_id = ?;";

        $this->query($query,[$date,$_SESSION['USER']->id]);

        //get patient ids for resheduled date
        $query = "SELECT a.id,a.appointment_id, p.nic, p.email FROM appointment a 
                JOIN patient p ON a.patient_id = p.id
                JOIN timeslot ts ON a.date = ts.slot_id
                WHERE a.doctor_id = ? AND ts.date = ?;";

        $patientIds = $this->query($query,[$_SESSION['USER']->id,$date]);
        //print_r($patientIds);

        $message = "Your appointment with Dr. " . $_SESSION['USER']->first_name . " " . $_SESSION['USER']->last_name . " on " . $date . " has been rescheduled. Kindly review and manage your appointment at your earliest convenience.";

        $email = new Email();
        foreach ($patientIds as $patient) {
            $email->send(
                "Wellbe",                    
                "wellbe@gmail.com",            
                $message,                  
                $patient->email,               
            );
        }

        //var_dump($date);
        //var_dump($patientIds);

        $docName = $_SESSION['USER']->first_name . " " . $_SESSION['USER']->last_name;
        $specialization = $_SESSION['USER']->specialization;

        // Loop through each patient and send notification
        if ($patientIds) {
            foreach ($patientIds as $patient) {
                $data = [
                    "type" => "reschedule",
                    "id" => $patient->id,
                    "patientId" => $patient->nic,
                    "docName" => $docName,
                    "specialization" => $specialization,
                    "date" => $date
                ];

                $options = [
                    'http' => [
                        'method'  => 'POST',
                        'header'  => "Content-Type: application/json\r\n",
                        'content' => json_encode($data),
                    ],
                ];

                $context = stream_context_create($options);
                file_get_contents('http://localhost:3000/notify', false, $context);
            }
        }

        redirect("doctor");
    }

    //get slot id using date
    public function getDateId($date){

        $query = "SELECT slot_id FROM timeslot WHERE date = :date";
        $data = ['date'=>$date];
        $dateId = $this->query($query,$data);

        return $dateId;
    }

    public function getAvailableDays($id){

        $query = "SELECT slot_id FROM timeslot WHERE date = :date";
        $data = ['date'=>date('Y-m-d')];
        $today = $this->query($query,$data);
        $todayId =  $today[0]->slot_id;

        $query = "SELECT slot_id, date FROM timeslot WHERE slot_id >= :todayId";
        $data = ['todayId'=>$todayId];
        $dates = $this->query($query,$data);
        /*foreach($dates as $date){
            echo $date->slot_id;
        }*/

        //get a doctors available dates
        $query = "SELECT start_time, slot_id FROM timeslot_doctor WHERE slot_id >= :todayId AND doctor_id = :id AND session = 'SET'";
        $data = ['todayId'=>$todayId, 'id'=>$id];
        $availableDays = $this ->query($query, $data);
        

        $matchedDates = [];
        
        foreach($dates as $date){
            foreach($availableDays as $availableDay){
                if($date->slot_id === $availableDay->slot_id){
                    $day = [
                        'slot_id' => $date->slot_id,
                        'day' => $date->date,
                        'start_time' => $availableDay->start_time
                    ];
                    
                    $matchedDates[] = $day;//faster than array_push
                }
            }
        }

        
        $dateDetails = ['matchedDates'=>$matchedDates,'todayId'=>$todayId];

        return $dateDetails;
    }

    public function getTodaySessions(){

        $query = "
        SELECT 
            t.slot_id,
            t.date AS date,
            td.start_time AS start_time,
            td.end_time AS end_time,
            d.id AS doctor_id,
            CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
            d.specialization AS specialization,
            COUNT(a.appointment_id) AS booked_slots
        FROM 
            timeslot_doctor td
        JOIN 
            timeslot t ON td.slot_id = t.slot_id
        JOIN 
            doctor d ON td.doctor_id = d.id
        LEFT JOIN 
            appointment a 
            ON a.doctor_id = td.doctor_id 
            AND a.date = t.slot_id 
            AND a.scheduled IN ('Scheduled', 'Rescheduled')  -- count only actual booked appointments
        WHERE 
            t.date = CURDATE() AND td.session = 'SET'
        GROUP BY 
            t.slot_id, t.date, td.start_time, td.end_time, d.id, d.first_name, d.last_name
        ";
        
        // Debug the query
        //echo("Generated Query: <pre>$query</pre>");

        return $this->query($query);
    }

    public function getUpcomingSessions(){

        $query = "
        SELECT 
            t.slot_id,
            t.date AS date,
            td.start_time AS start_time,
            td.end_time AS end_time,
            d.id AS doctor_id,
            CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
            d.specialization AS specialization,
            COUNT(a.appointment_id) AS booked_slots
        FROM 
            timeslot_doctor td
        JOIN 
            timeslot t ON td.slot_id = t.slot_id
        JOIN 
            doctor d ON td.doctor_id = d.id
        LEFT JOIN 
            appointment a 
            ON a.doctor_id = td.doctor_id 
            AND a.date = t.slot_id 
            AND a.scheduled IN ('Scheduled', 'Rescheduled')  -- count only actual booked appointments
        WHERE 
            t.date > CURDATE() AND td.session = 'SET'
        GROUP BY 
            t.slot_id, t.date, td.start_time, td.end_time, d.id, d.first_name, d.last_name
        ";
        
        // Debug the query
        //echo("Generated Query: <pre>$query</pre>");

        return $this->query($query);
    }

    public function getPastSessions(){

        $query = "
        SELECT 
            t.slot_id,
            t.date AS date,
            td.start_time AS start_time,
            td.end_time AS end_time,
            d.id AS doctor_id,
            CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
            d.specialization AS specialization,
            COUNT(a.appointment_id) AS booked_slots
        FROM 
            timeslot_doctor td
        JOIN 
            timeslot t ON td.slot_id = t.slot_id
        JOIN 
            doctor d ON td.doctor_id = d.id
        LEFT JOIN 
            appointment a 
            ON a.doctor_id = td.doctor_id 
            AND a.date = t.slot_id 
            AND a.scheduled IN ('Scheduled', 'Rescheduled')  -- count only actual booked appointments
        WHERE 
            t.date < CURDATE() AND td.session = 'SET'
        GROUP BY 
            t.slot_id, t.date, td.start_time, td.end_time, d.id, d.first_name, d.last_name
        ";
        
        // Debug the query
        //echo("Generated Query: <pre>$query</pre>");

        return $this->query($query);
    }

    public function getSessionData($slot_id, $doctor_id){
        $query = "
        SELECT 
            t.date AS date,
            td.start_time AS start_time,
            td.end_time AS end_time,
            CONCAT(d.first_name, ' ', d.last_name) AS doctor_name
        FROM 
            timeslot_doctor td
        JOIN 
            timeslot t ON td.slot_id = t.slot_id
        JOIN 
            doctor d ON td.doctor_id = d.id
        WHERE 
            td.slot_id = :slot_id AND td.doctor_id = :doctor_id
        ";

        $result = $this->query($query, [
            'slot_id' => $slot_id,
            'doctor_id' => $doctor_id
        ]);
        
        return $result ? $result[0] : false;
    }
}