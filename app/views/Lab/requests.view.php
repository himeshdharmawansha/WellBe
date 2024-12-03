<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Lab/labTestRequest.css">
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
         $pageTitle = "Test Requests"; // Set the text you want to display
         include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Lab/header.php';
         ?>
         <!-- Dashboard Content -->
         <div class="dashboard-content">

            <div class="tabs">
               <button class="tab active" onclick="showTab('new-requests')">New Requests</button>
               <button class="tab" onclick="showTab('ongoing-requests')">Ongoing Tests</button>
               <button class="tab" onclick="showTab('completed-requests')">Completed Tests</button>
            </div>

            <div class="search-container">
               <input type="text" class="search-input" placeholder="Search">
            </div>

            <!-- New Requests Section -->
            <div id="new-requests" class="requests-section active">
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
                  </tbody>
               </table>
            </div>

            <!-- Ongoing Requests Section -->
            <div id="ongoing-requests" class="requests-section">
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
                        <td>9:30 AM</td>
                        <td>05/12/2022</td>
                        <td>56481457</td>
                        <td>Dr. Joel</td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <!-- Completed Requests Section -->
            <div id="completed-requests" class="requests-section completed-requests">
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
                     <tr>
                        <td>2:00 PM</td>
                        <td>03/11/2022</td>
                        <td>56481456</td>
                        <td>Dr. Emily</td>
                     </tr>
                     <tr>
                        <td>2:00 PM</td>
                        <td>03/11/2022</td>
                        <td>56481456</td>
                        <td>Dr. Emily</td>
                     </tr>
                     <tr>
                        <td>2:00 PM</td>
                        <td>03/11/2022</td>
                        <td>56481456</td>
                        <td>Dr. Emily</td>
                     </tr>
                     <tr>
                        <td>2:00 PM</td>
                        <td>03/11/2022</td>
                        <td>56481456</td>
                        <td>Dr. Emily</td>
                     </tr>
                     <tr>
                        <td>2:00 PM</td>
                        <td>03/11/2022</td>
                        <td>56481456</td>
                        <td>Dr. Emily</td>
                     </tr>
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

         <script src="<?= ROOT ?>/assets/js/Lab/labTestRequest.js"></script>
         <script>
            // Add event listener for each table row
            document.querySelectorAll('.requests-table tbody tr').forEach(row => {
               row.addEventListener('click', function() {
                  const patientID = this.getAttribute('data-patient-id');
                  if (patientID) {
                     // Redirect to the details.php page with patientID as a query parameter
                     window.location.href = `labTestDetails?patientID=${patientID}`;
                  }
               });
            });
         </script>
      </div>
   </div>
</body>

</html>