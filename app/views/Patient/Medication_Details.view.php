<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medication Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/Medication_Details.css">
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
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
               <label for="dr-name">Doctor's Name:</label>
               <p>Dr. John</p>
               <label for="date">Date:</label>
               <p>24/5/2024</p>
               <label for="diagnosis">Diagnosis:</label>
               <p>Gastritis</p>
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
                  <tbody>
                     <tr>
                        <td>Medicine 1</td>
                        <td>2 mg</td>
                        <td>1</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td><input type="checkbox" checked></td>
                        
                     </tr>
                     <tr>
                        <td>Medicine 1</td>
                        <td>2 mg</td>
                        <td>1</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td><input type="checkbox" checked></td>
                        
                     </tr>
                     <tr>
                        <td>Medicine 1</td>
                        <td>2 mg</td>
                        <td>1</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td><input type="checkbox"  disabled></td>
                        
                     </tr>
                     <tr>
                        <td>Medicine 1</td>
                        <td>2 mg</td>
                        <td>1</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td><input type="checkbox" checked></td>
                        
                     </tr>
                     <!-- Add more rows as needed -->
                  </tbody>
               </table>
   
               <h2>LAB TESTS NEED TO BE TAKEN:</h2>
               <table>
                  <thead>
                     <tr>
                        <th>Name of the Test</th>
                        <th>Priority Level</th>
                        <th>Report</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>FBC</td>
                        <td>High</td>
                        <td onclick="window.location.href='Lab_download'"><button class="view">View</button></td>


                     </tr>
                     <tr>
                        <td>FBC</td>
                        <td>Medium</td>
                        <td onclick="window.location.href='Lab_download'"><button class="view">View</button></td>
                     </tr>
                     <tr>
                        <td>FBC</td>
                        <td>Low</td>
                        <td><button class="pending">Pending</button></td>
                     </tr>
                  </tbody>
               </table>
               

               <!-- Remarks Section -->
               <div class="remarks-section">
                  <h3>Remarks</h3>
                  <!-- Textarea for additional remarks -->
                  <textarea id="additionalRemarks" readonly>Please continue the medicine for 7 days, if you do not see a change please consult again</textarea>
               </div>
   
            
            </div>
         </div>
   
      </div>
   
                    
    </div>
</body>
</html>
