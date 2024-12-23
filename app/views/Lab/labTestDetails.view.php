<?php
require_once(__DIR__ . "/../../controllers/TestRequests.php");
$he = new TestRequests();
$requestID = isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : null;
$testDetails = $he->getTestDetails($requestID);
?>

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
      <?php $this->renderComponent('navbar', $active); ?>
      <div class="main-content">
         <?php $pageTitle = "Test Requests"; ?>
         <?php include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php'; ?>
         <div class="dashboard-content">
            <h2>Tests For Patient_ID: <?= $_GET['patient_id'] ?></h2>

            <?php if (isset($_GET['patient_id'])): ?>
               <?php $patientID = $_GET['patient_id']; ?>

               <div class="test-list" style="max-height: 450px; max-width: 800px;">
                  <table style="width: 100%; border-spacing: 0 10px;">
                     <thead>
                        <tr>
                           <th style="text-align: left; width: 45%;">Test Name</th>
                           <th style="text-align: left; width: 15%;">Priority</th>
                           <th style="text-align: left; width: 15%;">State</th>
                           <th style="text-align: left; width: 15%;">Upload File</th>
                           <th style="text-align: left; width: 10%;">Options</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($testDetails)): ?>
                           <?php foreach ($testDetails as $detail): ?>
                              <tr>
                                 <td><?= htmlspecialchars($detail['test_name']) ?></td>
                                 <td><?= htmlspecialchars($detail['priority']) ?></td>
                                 <td>
                                    <select name="state" data-test-name="<?= $detail['test_name'] ?>" class="state-selector">
                                       <option value="pending" <?= $detail['state'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                       <option value="ongoing" <?= $detail['state'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                       <option value="completed" <?= $detail['state'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                 </td>
                                 <td>
                                    <label class="upload-btn" for="file-input-<?= $detail['test_name'] ?>">
                                       Upload
                                       <i id="icon-<?= $detail['test_name'] ?>" class="fa-solid fa-circle-check" style="display: none; color: green; margin-right: 1.5px;"></i>
                                    </label>
                                    <input type="file" id="file-input-<?= $detail['test_name'] ?>" class="file-input" data-test-name="<?= $detail['test_name'] ?>" style="display: none;" disabled>
                                 </td>
                                 <td>
                                    <button style=" margin-right: 2px;">
                                       <i id="icon-<?= $detail['test_name'] ?>" class="fa-solid fa-eye" style="color: green;padding:5px;"></i>
                                    </button>
                                    <button>
                                       <i id="icon-<?= $detail['test_name'] ?>" class="fa-solid fa-trash" style="color: red;padding:5px;"></i>
                                    </button>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else: ?>
                           <tr>
                              <td colspan="4" style="text-align: center;">No test details found.</td>
                           </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>

               <div class="button-container">
                  <button class="completed-btn" id="doneButton" data-request-id="<?= $requestID ?>">Done</button>
               </div>

            <?php else: ?>
               <p>Invalid patient ID.</p>
            <?php endif; ?>

         </div>
      </div>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const fileInputs = document.querySelectorAll('.file-input');
         const stateSelectors = document.querySelectorAll('.state-selector');

         // Disable or enable the file input based on the selected state
         stateSelectors.forEach(select => {
            select.addEventListener('change', function() {
               const testName = this.dataset.testName;
               const fileInput = document.getElementById(`file-input-${testName}`);

               if (this.value === 'completed') {
                  fileInput.disabled = false; // Enable the upload input if "Completed" is selected
               } else {
                  fileInput.disabled = true; // Disable the upload input if not completed
               }
            });

            // Check the initial state on page load and update file input disabled state
            const testName = select.dataset.testName;
            const fileInput = document.getElementById(`file-input-${testName}`);
            if (select.value === 'completed') {
               fileInput.disabled = false; // Enable if already completed
            } else {
               fileInput.disabled = true; // Disable if not completed
            }
         });

         // Show/hide checkmark icon when file input changes
         fileInputs.forEach(input => {
            input.addEventListener('change', function() {
               const icon = document.getElementById(`icon-${this.dataset.testName}`); // Get the associated icon
               if (icon) {
                  if (this.files.length > 0) {
                     icon.style.display = 'inline'; // Show the checkmark icon
                  } else {
                     icon.style.display = 'none'; // Hide the icon if no file is selected
                  }
               }
            });
         });

         // Handle the 'Done' button functionality
         const doneButton = document.getElementById('doneButton');
         doneButton.addEventListener('click', function() {
            const requestID = doneButton.getAttribute('data-request-id');
            const remarks = document.getElementById('additionalRemarks').value;
            const rows = document.querySelectorAll('.test-table tbody tr');

            // Prepare data
            const tests = [];
            rows.forEach(row => {
               const testName = row.cells[0].textContent.trim();
               const state = row.querySelector('select').value;

               tests.push({
                  testName,
                  state,
               });
            });

            // Send data to the server
            fetch('<?= ROOT ?>/testRequests/update', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                     requestID,
                     remarks,
                     tests,
                  }),
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     window.location.href = `requests`;
                  } else {
                     alert('Failed to update test request.');
                  }
               })
               .catch(error => console.error('Error:', error));
         });
      });
   </script>

</body>

</html>