<?php
session_start();

require 'dbconnection.php';

if (!isset($_GET['nic']) || empty($_GET['nic'])) {
  die("Invalid access");
}

$nic = htmlspecialchars($_GET['nic']);

if (!isset($_GET['nic']) || empty($_GET['nic'])) {
  die("Invalid NIC provided");
}

$nic = htmlspecialchars($_GET['nic']); // Sanitize input

// Prepare and execute the query
$stmt = $con->prepare("SELECT * FROM patient WHERE nic = ?");
$stmt->bind_param("s", $nic); // Bind NIC as a string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $patient = $result->fetch_assoc(); // Fetch patient data
} else {
  die("No patient found with NIC: " . htmlspecialchars($nic));
}

// Close resources
$stmt->close();
$con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report Download</title>
    <link rel="stylesheet" href="./Lab_download.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
    <?php
        $this->renderComponent('navbar', $active);
        ?>
      

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WellBe-1/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                
                <div class="report-container">
                    <p>Lab Report - 11/2/2024<hr></p>
                    
                    <div class="report">
                        <img src="../assests/lab_report.jpeg">

                    </div>
                </div >
                <div class="button-container">
                    <button class="action-button">Download Report</button>
                    <button class="action-button">Share Report</button>
                </div>
            </div>
    </div>
</body>
</html>
