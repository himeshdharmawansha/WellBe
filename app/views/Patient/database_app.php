<?php
include("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nic = $_POST['nic'];
    $doctor = $_POST['doctor'];
    $specialization = $_POST['specialization'];
    $appointment_date = $_POST['appointment_date'];
    $payment_status = $_POST['payment_status'];

    // Prepare and execute the insert query
    $stmt = $con->prepare("INSERT INTO appointment (nic, doctor_name, specialization, appointment_date, payment_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nic, $doctor, $specialization, $appointment_date, $payment_status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Appointment saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving appointment']);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
