<!-- HTML Part -->
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Lab/labTestDetails.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
      <?php
      $this->renderComponent('navbar', $active);
      ?>
      <div class="main-content">
         <?php
         $pageTitle = "Test Requests"; // Set the text you want to display
         include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Lab/header.php';
         ?>
         <div class="dashboard-content">
            <h2>THINGS NEED TO BE TESTED:</h2>

            <?php if (isset($_GET['patientID'])): ?>
               <?php
               $patientID = $_GET['patientID'];

               if ($patientID == '56481321') {
                  $patientName = 'John Doe';
                  $medicationDetails = 'Blood test, Urine test';
               } elseif ($patientID == '56481457') {
                  $patientName = 'Jane Smith';
                  $medicationDetails = 'X-ray, MRI scan';
               } else {
                  $patientName = 'Unknown';
                  $medicationDetails = 'No details available';
               }
               ?>

               <div class="test-list" style="max-height: 450px; max-width: 800px;">
                  <table style="width: 100%; border-spacing: 0 10px;">
                     <thead>
                        <tr>
                           <th style="text-align: left; width: 80%;">Patient ID: <?= $patientID ?></th>
                           <th style="text-align: left; width: 30%;">State</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td style="text-align: left;">Blood Test</td>
                           <td style="text-align: left;">
                              <select>
                                 <option value="pending">Pending</option>
                                 <option value="progress">Progress</option>
                                 <option value="tested">Tested</option>
                              </select>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>

               <div class="button-container">
                  <button class="upload-btn" onclick="openReportPopup('<?= $patientID ?>')">Upload</button>
                  <button class="completed-btn">Completed</button>
               </div>
            <?php else: ?>
               <p>Invalid patient ID.</p>
            <?php endif; ?>


            <!-- Modal for Reports -->
            <div id="reportPopup" class="modal">
               <div class="modal-content">
                  <span class="close" onclick="closeReportPopup()">&times;</span>
                  <h2>Files</h2>
                  <p id="patientId">12345</p>
                  <table class="file-table">
                     <tbody id="reportTableBody"></tbody>
                  </table>
                  <div class="popup-actions">
                     <button id="uploadButton" onclick="uploadFile()">Upload <i class="fa fa-paperclip"></i></button>
                     <button id="saveButton" onclick="saveReports()">Save</button>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>

   <script src="<?= ROOT ?>/assets/js/Lab/details.js"></script>
</body>

</html>