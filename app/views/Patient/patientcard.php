<?php
// Include database connection
include('db_connection.php'); // Assuming you have a separate file for database connection

// Get patient_id from session or URL
$patient_id = $_GET['patient_id'];

// Query to fetch patient details
$query = "SELECT * FROM patient WHERE id = '$patient_id'";
$result = mysqli_query($con, $query);

// Check if a patient was found
if (mysqli_num_rows($result) > 0) {
    // Fetch the patient data
    $patient = mysqli_fetch_assoc($result);
} else {
    // Handle case where no patient is found
    echo "No patient found with the given ID.";
}
?>
