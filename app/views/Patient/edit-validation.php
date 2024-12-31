<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Sanitize inputs
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $contact = htmlspecialchars($_POST['contact']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $medical_history = htmlspecialchars($_POST['medical_history']);
    $allergies = htmlspecialchars($_POST['allergies']);

    // Validation
    if (empty($first_name)) {
        $errors[] = "First Name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "Last Name is required.";
    }
    if (empty($contact) || !preg_match('/^\d{10}$/', $contact)) {
        $errors[] = "Contact must be a 10-digit number.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    // If no errors, proceed with the update
    if (empty($errors)) {
        $update_stmt = $con->prepare("UPDATE patient SET first_name = ?, last_name = ?, contact = ?, email = ?, address = ?, medical_history = ?, allergies = ? WHERE nic = ?");
        $update_stmt->bind_param("ssssssss", $first_name, $last_name, $contact, $email, $address, $medical_history, $allergies, $nic);
        
        if ($update_stmt->execute()) {
            echo "<script> window.location.href='patient_dashboard.php?nic=$nic';</script>";
        } else {
            echo "<script>alert('Error updating profile.');</script>";
        }

        $update_stmt->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p class='error-message'>$error</p>";
        }
    }
}








?>