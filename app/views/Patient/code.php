<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user inputs
    $full_name = htmlspecialchars($_POST['full_name']);
    $nationality = htmlspecialchars($_POST['nationality']);
    $national_id = htmlspecialchars($_POST['national_id']);
    $email = htmlspecialchars($_POST['email']);
    $contact = htmlspecialchars($_POST['contact']);
    $emergency_contact = htmlspecialchars($_POST['emergency_contact']);
    $save_records = isset($_POST['save_records']) ? 'YES' : 'NO';

    // Save details to session
    $_SESSION['full_name'] = $full_name;
    $_SESSION['nationality'] = $nationality;
    $_SESSION['national_id'] = $national_id;
    $_SESSION['email'] = $email;
    $_SESSION['contact'] = $contact;
    $_SESSION['emergency_contact'] = $emergency_contact;
    

    // Redirect to hello.php
    header("Location: hello.php");
    exit();
}
?>
