<?php

// Check if session schedule is set
if (isset($_SESSION['schedule'])) {
    $schedule = $_SESSION['schedule'];
    //print_r($schedule);
    if(empty($schedule)){
        $schedule = [];
    }
    
} else {
    $schedule = []; // Default to an empty array if not set
    //echo "helooooo";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Access the data sent from the form
    if(isset($_POST["date"])){
        $timeSlot = $_POST['timeSlot'];
        $date = $_POST['date'];
   

        $timeslot->updateSchedule($date,$timeSlot);
    }
    elseif(isset($_POST["scheduleDate"])){
        
        $deleteDate = $_POST['scheduleDate'];

        $timeslot->deleteDate($deleteDate);
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/calender.css?v=1.1">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/schedule.css?v=1.1">
    
    <script>
        // Pass PHP array to JavaScript
        const schedule = <?php echo json_encode($schedule); ?>;
    </script>
</head>


<body>
    <div id="popupForm" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Schedule Details</h2>
            <form id="scheduleForm" method="post">
                <input type="hidden" name="date" id="date">
                <label for="timeSlots">Enter Check-Up Start Time</label>
                <div id="timeSlotContainer">
                    <input type="time" id="timeSlot" name="timeSlot[]" placeholder="Enter checkup time(08.00-12.00)" required>
                </div>
                <label for="timeSlots">Enter Check-Up End Time</label>
                <div id="timeSlotContainer">
                    <input type="time" id="timeSlot" name="timeSlot[]" placeholder="Enter checkup time(08.00-12.00)" required>
                </div>
                <button type="submit" class="save_btn">Save</button>
            </form>
        </div>
    </div>

    <div id="appointmentPopup" class="modal">
        <div class="modal-content">
            <span class="close" id="appointmentClose">&times;</span>
            <h2 style="font-weight: bold;text-align:center;margin-bottom:20px">Appointments</h2>
            <div id="appointmentDetails">
                <table id="appointmentTable" style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px;">Appointment ID</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">First Name</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Last Name</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>
            <button id="rescheduleButton" style="margin-top: 20px; padding: 10px 20px; background-color: #fc2f39; color: white; border: none; border-radius: 5px; cursor: pointer;">Reschedule Appointments</button>
        </div>
    </div>

    <div id="confirmModal" class="modal" style="display: none;">
        <form method="POST" id="scheduleForm" class="modal-content" style="text-align: center;">
            <p style="font-weight: bold; margin-bottom: 20px;">
                Are you sure you want to reschedule appointments on 
                <span id="scheduleDateDisplay"></span>?
            </p>
            <!-- Hidden input to submit the date -->
            <input type="hidden" id="scheduleDate" name="scheduleDate">
            <button id="confirmYes" style="padding: 10px 20px; background-color: #2b85ec; color: white; border: none; border-radius: 5px; cursor: pointer;">Yes</button>
            <button id="confirmNo" type="button" style="padding: 10px 20px; background-color: #fc2f39; color: white; border: none; border-radius: 5px; cursor: pointer;">No</button>
        </form>
    </div>

    <div id="showAppointment" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Appointment Details</h2>
            <p>Date: <span id="appointmentDate"></span></p>
            <div id="appointmentList">
                <!-- Appointment details will be populated dynamically -->
            </div>
            <button onclick="closeModal('showAppointment')">Close</button>
        </div>
    </div>



    <div class="cal-dashboard calendar-container">
        <div class="calendar-header">
            <h3>Calendar</h3>
            <div class="calendar-nav">
                <button id="prevMonth">&lt;</button>
                <span id="monthYear"></span>
                <button id="nextMonth">&gt;</button>
            </div>
        </div>
        <table class="calendar-table">
            <thead>
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
            </thead>
            <tbody id="calendar-body">
                <!-- Calendar Dates will be generated dynamically -->
            </tbody>
        </table>
    </div>

    <script src="<?= ROOT ?>/assets/js/calender.js?v=1.1"></script>
    <script src="<?= ROOT ?>/assets/js/schedule.js?v=1.1"></script>
</body>
</html>