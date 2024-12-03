<?php

   //echo $_SESSION['appointment_id'];

   $patient_id = $data['id'];

   if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $diagnosis = $_POST['diagnosis'];
      $medications = $_POST['medication_name'] ?? [];
      $dosages = $_POST['dosage'] ?? [];
      $morning = $_POST['morning'] ?? [];
      $noon = $_POST['noon'] ?? [];
      $night = $_POST['night'] ?? [];
      $if_needed = $_POST['if_needed'] ?? [];
      $do_not_substitute = $_POST['do_not_substitute'] ?? [];
      $lab_tests = $_POST['test_name'] ?? [];
      $priority = $_POST['priority'] ?? [];
      $remarks = $_POST['remarks'] ?? "";

      $medicationDetails = [];
      foreach ($medications as $index => $med) {
        $medicationDetails[] = [
            'name' => $med,
            'dosage' => $dosages[$index] ?? '',
            'morning' => $morning[$index] ?? 0,
            'noon' => $noon[$index] ?? 0,
            'night' => $night[$index] ?? 0,
            'if_needed' => $if_needed[$index] ?? 0,
            'do_not_substitute' => isset($do_not_substitute[$index]) ? true : false,
        ];
    }

     $labTestDetails = [];
     foreach($lab_tests as $index => $test){
        $labTestDetails[] = [
            'name' => $test,
            'priority' => $priority[$index] ?? '',
        ];
     }

     if(!empty($medications)){

      $medicalRecord = new MedicalRecord();

      $medicalRecord->insertRecord($remarks,$diagnosis, $patient_id);
      
      $request_id = $medicalRecord->getLastInsertedId($patient_id);

      foreach($medicationDetails as $medic){

         $medicalRecord->insertMed($medic,$request_id);
     }

     $appointments = new Appointments;
     $appointments->endAppointment($data['app_id']);
   }

   if(!empty($lab_tests)){

      $labTest = new LabTest();

      $labTest->insertRecord($patient_id);
      
      $request_id = $labTest->getLastInsertedId($patient_id);

      foreach($labTestDetails as $lab){

         $labTest->insertTest($lab,$request_id);
     }
   }

 }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medication Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Medication_Details.css">
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
            $pageTitle = "Medical Reports"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/test2/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                  <label for="dr-name">Doctor's Name:</label>
                  <p>Dr. <?php echo htmlspecialchars($_SESSION['USER']->first_name); ?> <?php echo htmlspecialchars($_SESSION['USER']->last_name); ?></p>
                  <label for="date">Date:</label>
                  <p><?php echo htmlspecialchars(date('Y-m-d')); ?></p>
                  <form method="post" action="">
                     <label for="diagnosis">Diagnosis:</label>
                     <input type="text" name="diagnosis" placeholder="Gastritis" style="font-size: 17px;margin-bottom:10px;">
                     <h2>MEDICINES NEED TO BE GIVEN:</h2>
                     <table class="medication-table">
                        <thead>
                           <tr>
                              <th>Name of the Medication</th>
                              <th>Dosage of the Medication</th>
                              <th colspan="4">Number taken at a time</th>
                              <th>Do not substitute</th>
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
                        <tbody id="prescription-body">
                           <tr>
                              <td><input type="text" name="medication_name[]" placeholder="Medicine Name"></td>
                              <td><input type="text" name="dosage[]" placeholder="Dosage"></td>
                              <td><input type="number" name="morning[]" min="0" placeholder="0"></td>
                              <td><input type="number" name="noon[]" min="0" placeholder="0"></td>
                              <td><input type="number" name="night[]" min="0" placeholder="0"></td>
                              <td><input type="number" name="if_needed[]" min="0" placeholder="0"></td>
                              <td><input type="checkbox" name="do_not_substitute[]"></td>
                           </tr>
                        </tbody>
                     </table>
                     <button class="add-row-btn" onclick="addMedicationRow(event)" style="margin-bottom: 15px;">+ Add Another Medication</button>

                     <h2>LAB TESTS NEED TO BE TAKEN:</h2>
                     <table>
                        <thead>
                           <tr>
                              <th>Name of the Test</th>
                              <th>Priority Level</th>
                              <th>Report</th>
                           </tr>
                        </thead>
                        <tbody id="prescription-body1">
                           <tr>
                              <td><input type="text" name="test_name[]" placeholder="Test Name"></td>
                              <td><input type="text" name="priority[]" placeholder="Priority"></td>
                              <td onclick="window.location.href='Lab_download'"><button class="view">View</button></td>
                           </tr>
                        </tbody>
                     </table>
                     <button class="add-row-btn" onclick="addLabTestRow(event)" style="margin-bottom: 15px;">+ Add Another Test</button>

                     <!-- Remarks Section -->
                     <div class="remarks-section">
                        <h3>Remarks</h3>
                        <input id="additionalRemarks" type="text" name="remarks" placeholder="State remarks if there is any" style="font-size: 16px;">
                     </div>

                     <!-- Submit button inside the form -->
                     <button type="submit" value="submit">Submit</button>
                  </form>

      
               
               </div>
         </div>
   
      </div>
                 
    </div>
   </form>

    <script>
      function addMedicationRow(event) {
         event.preventDefault();
         // Get the table body
         const tableBody = document.getElementById('prescription-body');

         // Create a new row
         const newRow = document.createElement('tr');
         newRow.innerHTML = `
            <td><input type="text" name="medication_name[]" placeholder="Medicine Name"></td>
            <td><input type="text" name="dosage[]" placeholder="Dosage"></td>
            <td><input type="number" name="morning[]" min="0" placeholder="0"></td>
            <td><input type="number" name="noon[]" min="0" placeholder="0"></td>
            <td><input type="number" name="night[]" min="0" placeholder="0"></td>
            <td><input type="number" name="if_needed[]" min="0" placeholder="0"></td>
            <td><input type="checkbox" name="do_not_substitute[]"></td>
         `;

         // Append the new row to the table body
         tableBody.appendChild(newRow);
      }

      function addLabTestRow(event){
         event.preventDefault();
         const tableBody = document.getElementById('prescription-body1');

         const newRow = document.createElement('tr');
         newRow.innerHTML = `
                  <td><input type="text" name="test_name[]" placeholder="Test Name"></td>
                  <td><input type="text" name="priority[]" placeholder="Prioroty"></td>
                  <td onclick="window.location.href='Lab_download'"><button class="view">View</button></td>
         `;

         tableBody.appendChild(newRow);
      }
   </script>
</body>
</html>
