<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>WELLBE</title>
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
         $pageTitle = "Medication Requests";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>

         <!-- Dashboard Content -->
         <div class="dashboard-content">
            <h2>MEDICINES NEED TO BE GIVEN:</h2>
            <?php
            // Retrieve and sanitize GET parameters
            $requestID = isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : null;
            $doctorID = isset($_GET['doctor_id']) ? htmlspecialchars($_GET['doctor_id']) : null;
            $patientID = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : null;

            if ($requestID && $doctorID && $patientID) {
               $db = new Database();

               // Fetch medication details excluding remarks
               $query = "SELECT medication_name, dosage, taken_time, substitution, state 
                         FROM medication_request_details 
                         WHERE req_id = :req_id";
               $medicationDetails = $db->read($query, ['req_id' => $requestID]);

               // Fetch remarks for the request
               $remarksQuery = "SELECT remark FROM medication_requests WHERE id = :req_id";
               $remarksResult = $db->read($remarksQuery, ['req_id' => $requestID]);
               $additionalRemarks = $remarksResult[0]['remark'] ?? '';

               if ($medicationDetails) {
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
                         <tbody>";

                  foreach ($medicationDetails as $medication) {
                     $takenTimeArray = explode(' ', $medication['taken_time']);
                     $morning = $takenTimeArray[0] ?? 0;
                     $noon = $takenTimeArray[1] ?? 0;
                     $night = $takenTimeArray[2] ?? 0;
                     $ifNeeded = $takenTimeArray[3] ?? 0;
                     $substitution = $medication['substitution'] == 0 ? "can't" : "can";

                     $currentState = htmlspecialchars($medication['state']);

                     echo "<tr>
                               <td>{$medication['medication_name']}</td>
                               <td>{$medication['dosage']}</td>
                               <td>{$morning}</td>
                               <td>{$noon}</td>
                               <td>{$night}</td>
                               <td>{$ifNeeded}</td>
                               <td>{$substitution}</td>
                               <td>
                                   <select>
                                       <option value='pending' " . ($currentState === 'pending' ? 'selected' : '') . ">Pending</option>
                                       <option value='given' " . ($currentState === 'given' ? 'selected' : '') . ">Given</option>
                                       <option value='notavailable' " . ($currentState === 'notavailable' ? 'selected' : '') . ">Not available</option>
                                   </select>
                               </td>
                             </tr>";
                  }

                  echo "</tbody>
                         </table>";

                  // Remarks section
                  echo "<div class='remarks-section'>
                           <h3>Remarks</h3>
                           <p>Patient ID: {$patientID}</p>
                           <p>Doctor ID: {$doctorID}</p>
                           <p>Date: <span id='currentDate'></span></p>
                           <textarea id='additionalRemarks' placeholder='Enter additional remarks...'>" . htmlspecialchars($additionalRemarks) . "</textarea>
                         </div>";

                  // Buttons
                  echo "<div class='buttons'>
                           <button class='btn done' id='doneButton' data-request-id={$requestID}>Done</button>
                           <button class='btn remarks' id='remarksButton'>Print</button>
                         </div>";
               } else {
                  echo "<p>No medication details found for Request ID {$requestID}.</p>";
               }
            } else {
               echo "<p>Missing or invalid request details. Please provide valid Request ID, Doctor ID, and Patient ID.</p>";
            }

            ?>
         </div>
      </div>

      <!-- Include JavaScript for dynamic features -->
      <script src="<?= ROOT ?>/assets/js/Pharmacy/remarkPopup.js"></script>
      <script>
         // Set the current date dynamically in the remarks section
         document.getElementById('currentDate').textContent = new Date().toLocaleDateString();
         document.addEventListener('DOMContentLoaded', function() {
            const doneButton = document.getElementById('doneButton');

            doneButton.addEventListener('click', function() {
               const requestID = doneButton.getAttribute('data-request-id');
               const remarks = document.getElementById('additionalRemarks').value;
               const rows = document.querySelectorAll('.medication-table tbody tr');

               // Prepare data
               const medications = [];
               rows.forEach(row => {
                  const medicationName = row.cells[0].textContent.trim();
                  const state = row.querySelector('select').value;

                  medications.push({
                     medicationName,
                     state,
                  });
               });

               // Send data to the server
               fetch('<?= ROOT ?>/MedicationRequests/update', {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                        requestID,
                        remarks,
                        medications,
                     }),
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        //alert('Medication request updated successfully.');
                        window.location.href = `requests`;
                     } else {
                        alert('Failed to update medication request.');
                     }
                  })
                  .catch(error => console.error('Error:', error));
            });
         });

      </script>
   </div>
</body>

</html>