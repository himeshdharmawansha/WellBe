<?php
// signup.php - Handle both form submissions

session_start();
include ('dbconnection.php'); // Include database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Form 1 submission (personal info)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['first_name'])) {
    // Save Form 1 data to session
    $_SESSION['form1_data'] = $_POST;

    // Redirect to Form 2 to prevent form resubmission
    header("Location: ./form2");
    exit();
}

// Handle Form 2 submission (health & emergency info)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['medical_history'])) {
    // Retrieve Form 1 and Form 2 data
    $form1_data = $_SESSION['form1_data'];
    $form2_data = $_POST;

    // Merge both sets of data
    $patient_data = array_merge($form1_data, $form2_data);

    // Prepare the SQL query
    $stmt = $con->prepare("
        INSERT INTO patient (
            first_name, last_name, nic,password, dob, gender, address, email, contact, 
            medical_history, allergies, emergency_contact_name, emergency_contact_no, 
            emergency_contact_relationship
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
    ");

    // Check if the statement was prepared successfully
    if (!$stmt) {
        die("Database error: " . $con->error);
    }
    
    // Bind parameters to the statement
    $stmt->bind_param(
        'ssssssssssssss',
        $patient_data['first_name'],
        $patient_data['last_name'],
        $patient_data['nic'],
        $patient_data['password'],
        $patient_data['dob'],
        $patient_data['gender'],
        $patient_data['address'],
        $patient_data['email'],
        $patient_data['contact'],
        $patient_data['medical_history'],
        $patient_data['allergies'],
        $patient_data['emergency_contact_name'],
        $patient_data['emergency_contact_no'],
        $patient_data['emergency_contact_relationship']
    );

    // Execute the statement
    if ($stmt->execute()) {
        // Clear session data after successful registration
        unset($_SESSION['form1_data']);

        // Redirect to login page to prevent resubmission
        header("Location: ./login");
        exit();
    } else {
        // Handle query execution errors
        die("Error saving data: " . $stmt->error);
    }

    // Close the statement
    //$stmt->close();
}

// Close the database connection
$con->close();
?>
