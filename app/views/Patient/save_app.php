<?php
session_start();
include("dbconnection.php");

if (!isset($_GET['nic']) || empty($_GET['nic'])) {
    die("Access denied: NIC is missing in the URL");
}

$nic = htmlspecialchars($_GET['nic']); // Retrieve and sanitize NIC


$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $doctor = htmlspecialchars(trim($_POST['doctor']));
    $specialization = htmlspecialchars(trim($_POST['specialization']));
    $date = htmlspecialchars(trim($_POST['date-input']));

    // Validate inputs
    if (empty($doctor)) {
        $errors['doctor'] = "Please select a doctor.";
    } else {
        // Validate against database
        $stmt = $con->prepare("SELECT 1 FROM doctor WHERE first_name = ?");
        $stmt->bind_param("s", $doctor);
        $stmt->execute();
        if (!$stmt->get_result()->num_rows) {
            $errors['doctor'] = "Selected doctor does not exist.";
        }
        $stmt->close();
    }

    if (empty($specialization)) {
        $errors['specialization'] = "Please select a specialization.";
    }

    if (empty($date)) {
        $errors['date'] = "Please select a date.";
    }

    if (empty($errors)) {
        // Store data in session variables
        $_SESSION['doctor'] = $doctor;
        $_SESSION['specialization'] = $specialization;
        $_SESSION['date'] = $date;

        // Redirect to the display page
        header("Location: hello.php?nic=" .urlencode($nic));
        exit();
    } else {
        // Store errors in session and redirect back
        $_SESSION['errors'] = $errors;
        header("Location: doc_appointment.php?nic=" .urlencode($nic));
    }
}
?>
