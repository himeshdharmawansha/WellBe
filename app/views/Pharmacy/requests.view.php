<?php
require_once(__DIR__ . "/../../core/Database.php");
$DB = new Database();

$pendingRequests = $DB->read("SELECT * FROM medication_requests WHERE state in ('pending', 'progress') ORDER BY id DESC ");
$progressRequests = $DB->read("SELECT * FROM medication_requests WHERE state = 'progress' ORDER BY id DESC");
$completedRequests = $DB->read("SELECT * FROM medication_requests WHERE state = 'completed' ORDER BY id DESC");


?>

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
      <?php $this->renderComponent('navbar', $active); ?>

      <!-- Main Content -->
      <div class="main-content">
         <!-- Top Header -->
         <?php
         $pageTitle = "Medication Requests";
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
               <input type="text" class="search-input" placeholder="Search PatientID" id="searchPatientId">
            </div>


            <!-- Requests Sections -->
            <div id="pending-requests" class="requests-section active">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor ID</th>
                     </tr>
                  </thead>
                  <tbody id="pending-requests-body">
                     <?php if (!empty($pendingRequests)): ?>
                        <?php foreach ($pendingRequests as $request): ?>
                           <tr data-id="<?= htmlspecialchars($request['id']) ?>">
                              <td><?= date('h:i A', strtotime($request['time'])) ?></td>
                              <td><?= htmlspecialchars($request['date']) ?></td>
                              <td><?= htmlspecialchars($request['patient_id']) ?></td>
                              <td><?= htmlspecialchars($request['doctor_id']) ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php else: ?>
                        <tr>
                           <td colspan="4">No pending requests found.</td>
                        </tr>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>

            <div id="progress-requests" class="requests-section">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor ID</th>
                     </tr>
                  </thead>
                  <tbody id="progress-requests-body">
                     <?php if (!empty($progressRequests)): ?>
                        <?php foreach ($progressRequests as $request): ?>
                           <tr data-id="<?= $request['id'] ?>">
                              <td><?= date('h:i A', strtotime($request['time'])) ?></td>
                              <td><?= htmlspecialchars($request['date']) ?></td>
                              <td><?= htmlspecialchars($request['patient_id']) ?></td>
                              <td><?= htmlspecialchars($request['doctor_id']) ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php else: ?>
                        <tr>
                           <td colspan="4">No progress requests found.</td>
                        </tr>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>

            <div id="completed-requests" class="requests-section">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor ID</th>
                     </tr>
                  </thead>
                  <tbody id="completed-requests-body">
                     <?php if (!empty($completedRequests)): ?>
                        <?php foreach ($completedRequests as $request): ?>
                           <tr data-id="<?= $request['id'] ?>">
                              <td><?= date('h:i A', strtotime($request['time'])) ?></td>
                              <td><?= htmlspecialchars($request['date']) ?></td>
                              <td><?= htmlspecialchars($request['patient_id']) ?></td>
                              <td><?= htmlspecialchars($request['doctor_id']) ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php else: ?>
                        <tr>
                           <td colspan="4">No completed requests found.</td>
                        </tr>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>

            <!-- Pagination -->
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
         // Update state to 'progress' on row click
         document.addEventListener('click', e => {
            // Check if the click is inside the pending-requests section
            const pendingRequestsSection = document.getElementById('pending-requests');
            const row = e.target.closest('tr[data-id]');

            if (pendingRequestsSection.contains(row)) { // Make sure the row is inside the pending-requests section
               const requestID = row.getAttribute('data-id'); // Get the request ID

               // Send a POST request to update the state
               fetch('<?= ROOT ?>/MedicationRequests/updateState', {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                        requestID: requestID,
                        state: 'progress',
                     }),
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        // Refresh data or manually move the row to the "progress" section
                        row.remove(); // Remove from Pending table
                        document.getElementById('progress-requests-body').appendChild(row); // Add to Progress table
                     } else {
                        alert('Failed to update state: ' + data.error);
                     }
                  })
                  .catch(console.error);
            }
         });


         // Format time to hh:mm AM/PM
         function formatTimeToAmPm(time) {
            const date = new Date(`1970-01-01T${time}Z`); // Create a Date object from time
            return date.toLocaleTimeString([], {
               hour: '2-digit',
               minute: '2-digit',
               hour12: true
            });
         }

         // // Poll for new data every 2 seconds
         // setInterval(() => {
         //    fetch('<?= ROOT ?>/MedicationRequests/getRequestsJson')
         //       .then(response => response.json())
         //       .then(data => {
         //          const pending = document.getElementById('pending-requests-body');
         //          const progress = document.getElementById('progress-requests-body');
         //          const completed = document.getElementById('completed-requests-body');

         //          // Clear existing table data
         //          pending.innerHTML = '';
         //          progress.innerHTML = '';
         //          completed.innerHTML = '';

         //          // Iterate over the data
         //          data.forEach(request => {
         //             // Parse time without assuming it is UTC
         //             const [hours, minutes, seconds] = request.time.split(':'); // Split the time string into components
         //             const date = new Date(); // Create a new Date object
         //             date.setHours(hours, minutes, seconds || 0); // Set the hours, minutes, and optional seconds

         //             // Format the time as h:i A (e.g., 02:30 PM)
         //             const formattedTime = date.toLocaleTimeString('en-US', {
         //                hour: '2-digit',
         //                minute: '2-digit',
         //                hour12: true // Enables AM/PM format
         //             });

         //             // Create a row with the formatted time
         //             const row = `
         //             <tr data-id="${request.id}">
         //                <td>${formattedTime}</td>
         //                <td>${request.date}</td>
         //                <td>${request.patient_id}</td>
         //                <td>${request.doctor_id}</td>
         //             </tr>`;

         //             // Append rows to the appropriate table body
         //             if (request.state === 'progress' || request.state === 'pending') pending.innerHTML += row;
         //             if (request.state === 'progress') progress.innerHTML += row;
         //             if (request.state === 'completed') completed.innerHTML += row;
         //          });
         //       })
         //       .catch(console.error);
         // }, 2000);


         // Declare a variable to track search state
         let isSearching = false;

         // Poll for new data every 2 seconds
         setInterval(() => {
            // Only poll if no search is active
            if (!isSearching) {
               fetch('<?= ROOT ?>/MedicationRequests/getRequestsJson')
                  .then(response => response.json())
                  .then(data => {
                     const pending = document.getElementById('pending-requests-body');
                     const progress = document.getElementById('progress-requests-body');
                     const completed = document.getElementById('completed-requests-body');

                     // Clear existing table data
                     pending.innerHTML = '';
                     progress.innerHTML = '';
                     completed.innerHTML = '';

                     // Iterate over the data
                     data.forEach(request => {
                        // Parse time without assuming it is UTC
                        const [hours, minutes, seconds] = request.time.split(':');
                        const date = new Date();
                        date.setHours(hours, minutes, seconds || 0);

                        const formattedTime = date.toLocaleTimeString('en-US', {
                           hour: '2-digit',
                           minute: '2-digit',
                           hour12: true
                        });

                        const row = `
               <tr data-id="${request.id}">
                  <td>${formattedTime}</td>
                  <td>${request.date}</td>
                  <td>${request.patient_id}</td>
                  <td>${request.doctor_id}</td>
               </tr>`;

                        // Append rows to the appropriate table body
                        if (request.state === 'progress' || request.state === 'pending') pending.innerHTML += row;
                        if (request.state === 'progress') progress.innerHTML += row;
                        if (request.state === 'completed') completed.innerHTML += row;
                     });
                  })
                  .catch(console.error);
            }
         }, 1000);

         // Format time to hh:mm AM/PM
         function formatTimeToAmPm(time) {
            const [hours, minutes, seconds] = time.split(':'); // Split the time string into components
            const date = new Date(); // Create a new Date object
            date.setHours(hours, minutes, seconds || 0); // Set the hours, minutes, and optional seconds

            return date.toLocaleTimeString('en-US', {
               hour: '2-digit',
               minute: '2-digit',
               hour12: true // Enables AM/PM format
            });
         }

         // Handle search input change
         document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            if (searchTerm) {
               // Set the flag to true when searching
               isSearching = true;

               fetch(`<?= ROOT ?>/MedicationRequests/searchRequestsByPatientId?patient_id=${searchTerm}`)
                  .then(response => response.json())
                  .then(data => {
                     // Clear previous results
                     const requestBodies = document.querySelectorAll('.requests-section tbody');
                     requestBodies.forEach(body => body.innerHTML = '');

                     // Handle search results
                     if (data.length) {
                        data.forEach(request => {
                           const formattedTime = formatTimeToAmPm(request.time); // Format the time

                           const row = `
                            <tr data-id="${request.id}">
                                <td>${formattedTime}</td>
                                <td>${request.date}</td>
                                <td>${request.patient_id}</td>
                                <td>${request.doctor_id}</td>
                            </tr>`;




                           // If the request state is "pending", also add it to the "progress" section
                           if (request.state == "progress") {
                              const tableBody = document.querySelector("#pending-requests-body");
                              tableBody.innerHTML += row;

                              // Add rows to the appropriate table based on request state
                              const tableBodyP = document.querySelector(`#${request.state}-requests-body`);
                              tableBodyP.innerHTML += row;
                           } else {
                              // Add rows to the appropriate table based on request state
                              const tableBody = document.querySelector(`#${request.state}-requests-body`);
                              tableBody.innerHTML += row;
                              
                           }

                        });
                     } else {
                        alert('No results found.');
                     }
                  })
                  .catch(console.error);
            } else {
               // Reset the flag when search input is cleared
               isSearching = false;
            }
         });

         // Redirect to details page on row click
         document.addEventListener('click', e => {
            const row = e.target.closest('tr[data-id]');
            if (row) {
               const requestID = row.getAttribute('data-id'); // Get the request ID
               const doctorID = row.querySelector('td:nth-child(4)').textContent.trim(); // Doctor ID
               const patientID = row.querySelector('td:nth-child(3)').textContent.trim(); // Patient ID

               // Redirect with all parameters in the query string
               window.location.href = `medicationDetails?ID=${encodeURIComponent(requestID)}&doctor_id=${encodeURIComponent(doctorID)}&patient_id=${encodeURIComponent(patientID)}`;
            }
         });
      </script>
</body>

</html>