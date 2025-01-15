<?php
session_start();
include("dbconnection.php");

// Check if a specific request parameter is provided
$doctor = isset($_POST['doctor']) ? $_POST['doctor'] : null;
$specialization = isset($_POST['specialization']) ? $_POST['specialization'] : null;
$requestType = isset($_POST['type']) ? $_POST['type'] : null; // Added to identify request type

if ($doctor) {
   // Fetch specialization(s) based on doctor
   $query = "SELECT * FROM doctor WHERE first_name = ?";
   $stmt = $con->prepare($query);
   $stmt->bind_param("s", $doctor);
   $stmt->execute();
   $result = $stmt->get_result();
   //$_SESSION['result'] = $result;


   $specializations = [];
   while ($row = $result->fetch_assoc()) {
      $specializations[] = $row['specialization'];
      $docId = $row['doctor_id'];
   }

   $_SESSION['result'] = $docId;

   echo json_encode($specializations);
   
   //  $doctorDetails = []; // Array to hold the query results
   //  while ($row = $result->fetch_assoc()) {
   //      $doctorDetails[] = $row; // Fetch each row as an associative array
   //  }

   //  // Store the fetched array in the session
   //  $_SESSION['result'] = $doctorDetails;

   //  echo "Data saved to session.";



   /*$query1 = "SELECT * FROM doctor WHERE first_name = ?";
   $stmt = $con->prepare($query1);
   $stmt->bind_param("s", $doctor);
   $stmt->execute();
   $result2 = $stmt->get_result();
   print_r($result2);*/

   
} elseif ($specialization) {
   // Fetch doctor(s) based on specialization
   $query = "SELECT DISTINCT first_name FROM doctor WHERE specialization = ?";
   $stmt = $con->prepare($query);
   $stmt->bind_param("s", $specialization);
   $stmt->execute();
   $result = $stmt->get_result();

   $doctors = [];
   while ($row = $result->fetch_assoc()) {
      $doctors[] = $row['first_name'];
   }

   echo json_encode($doctors);
} else {
   // Default case: Fetch all doctors and specializations
   $query = "SELECT DISTINCT first_name, specialization FROM doctor";
   $result = $con->query($query);

   $data = [];
   while ($row = $result->fetch_assoc()) {
      $data[] = $row;
   }

   echo json_encode($data);
}
