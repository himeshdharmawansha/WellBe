<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/medicationRequestList.css">
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

            <div class="tabs">
               <button class="tab active" onclick="showTab('pending-requests')">Pending Requests</button>
               <button class="tab" onclick="showTab('progress-requests')">Progress Requests</button>
               <button class="tab" onclick="showTab('completed-requests')">Completed Requests</button>
            </div>

            <div class="search-container">
               <input type="text" class="search-input" placeholder="Search">
            </div>

            <!-- Pending Requests Section -->
            <div id="pending-requests" class="requests-section active">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr data-patient-id="56481321">
                        <td>9:30 AM</td>
                        <td>05/12/2022</td>
                        <td>56481321</td>
                        <td>Dr. John</td>
                     </tr>
                     <tr data-patient-id="56481457">
                        <td>9:30 AM</td>
                        <td>05/12/2022</td>
                        <td>56481457</td>
                        <td>Dr. Joel</td>
                     </tr>
                     <!-- More Pending Requests Rows Here -->
                  </tbody>
               </table>
            </div>

            <!-- Progress Requests Section -->
            <div id="progress-requests" class="requests-section">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr data-patient-id="56481523">
                        <td>10:00 AM</td>
                        <td>06/12/2022</td>
                        <td>56481523</td>
                        <td>Dr. Alice</td>
                     </tr>
                     <!-- More Progress Requests Rows Here -->
                  </tbody>
               </table>
            </div>

            <!-- Completed Requests Section -->
            <div id="completed-requests" class="requests-section">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>2:00 PM</td>
                        <td>03/11/2022</td>
                        <td>56481456</td>
                        <td>Dr. Emily</td>
                     </tr>
                     <!-- More Completed Requests Rows Here -->
                  </tbody>
               </table>
            </div>

            <div class="pagination">
               <button class="pagination-btn">Previous</button>
               <button class="pagination-page active">1</button>
               <button class="pagination-page">2</button>
               <button class="pagination-page">3</button>
               <button class="pagination-page">4</button>
               <button class="pagination-btn">Next</button>
            </div>
         </div>
      </div>
      <script src="<?= ROOT ?>/assets/js/Pharmacy/medicationRequestList.js"></script>
      <script>
         // Add event listener for each table row
         document.querySelectorAll('.requests-table tbody tr').forEach(row => {
            row.addEventListener('click', function() {
               const patientID = this.getAttribute('data-patient-id');
               if (patientID) {
                  // Redirect to the details.php page with patientID as a query parameter
                  window.location.href = `medicationDetails?patientID=${patientID}`;
               }
            });
         });
      </script>
</body>

</html>