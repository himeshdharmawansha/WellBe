<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/medicationDetails.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
      <!-- Sidebar -->
      <?php
      $this->renderComponent('navbar', $active);
      ?>
      <!-- Main Content -->
      <div class="main-content">
         <!-- Top Header -->
         <?php
         $pageTitle = "Medication Requests"; // Set the text you want to display
         include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Lab/header.php';
         ?>
         <!-- Dashboard Content -->
         <div class="dashboard-content">
            <h2>MEDICINES NEED TO BE GIVEN:</h2>
            <?php
            if (isset($_GET['patientID'])) {
               $patientID = $_GET['patientID'];

               // Using dummy data instead of querying the database
               if ($patientID == '56481321') {
                  $doctorID = '46546546';
               } elseif ($patientID == '56481457') {
                  $doctorID = '46587446';
               } else {
                  $doctorID = 'N/A';
               }

               // Output the medication table and remarks section
               echo "<table class='medication-table'>
                        <thead>
                           <tr>
                              <th>Name of the Medication</th>
                              <th>Dosage of the Medication</th>
                              <th colspan='4'>Number taken at a time</th>
                              <th>Do not substitute</th>
                              <th>State</th>
                           </tr>
                           <tr>
                              <th></th>
                              <th></th>
                              <th>Morning</th>
                              <th>Noon</th>
                              <th>Night</th>
                              <th>If Needed</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>Medicine 1</td>
                              <td>2 mg</td>
                              <td>1</td>
                              <td>2</td>
                              <td>2</td>
                              <td>2</td>
                              <td>can</td>
                              <td>
                                 <select>
                                    <option value='pending'>Pending</option>
                                    <option value='given'>Given</option>
                                    <option value='notavailable'>Not available</option>
                                 </select>
                              </td>
                           </tr>
                           <tr>
                              <td>Medicine 2</td>
                              <td>5 mg</td>
                              <td>1</td>
                              <td>1</td>
                              <td>1</td>
                              <td>0</td>
                              <td>can't</td>
                              <td>
                                 <select>
                                    <option value='pending'>Pending</option>
                                    <option value='given'>Given</option>
                                    <option value='notavailable'>Not available</option>
                                 </select>
                              </td>
                           </tr>
                           <!-- Add more rows as needed -->
                        </tbody>
                     </table>";

               // Remarks section
               echo "<div class='remarks-section'>
                        <h3>Remarks</h3>
                        <p>Patient ID: {$patientID}</p>
                        <p>Doctor ID: {$doctorID}</p>
                        <p>Date: <span id='currentDate'></span></p>
                        <textarea id='additionalRemarks' placeholder='Enter additional remarks...'></textarea>
                     </div>";

               // Buttons
               echo "<div class='buttons'>
                     <button class='btn done'>Done</button>
                     <button class='btn remarks' id='remarksButton'>Print</button>
                  </div>";
            } else {
               echo "<p>Invalid patient ID.</p>";
            }
            ?>

         </div>
      </div>

      <script src="<?= ROOT ?>/assets/js/Pharmacy/remarkPopup.js"></script>
   </div>
</body>

</html>