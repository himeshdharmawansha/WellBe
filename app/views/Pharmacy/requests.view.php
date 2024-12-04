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
               <input type="text" class="search-input" placeholder="Search">
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
                     <!-- Debugging output in the view -->
                     <?php if (!empty($pendingRequests)): ?>
                        <?php foreach ($pendingRequests as $request): ?>
                           <tr data-patient-id="<?= $request['patient_id'] ?>">
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
                           <tr data-patient-id="<?= $request['patient_id'] ?>">
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
                           <tr data-patient-id="<?= $request['patient_id'] ?>">
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
         // Format time to hh:mm AM/PM
         function formatTimeToAmPm(time) {
            const date = new Date(`1970-01-01T${time}Z`); // Create a Date object from time
            return date.toLocaleTimeString([], {
               hour: '2-digit',
               minute: '2-digit',
               hour12: true
            });
         }

         // Poll for new data every 5 seconds
         setInterval(() => {
            fetch('<?= ROOT ?>/MedicationRequestsController/getRequestsJson')
               .then(response => response.json())
               .then(data => {
                  const pending = document.getElementById('pending-requests-body');
                  const progress = document.getElementById('progress-requests-body');
                  const completed = document.getElementById('completed-requests-body');

                  // Clear existing table data
                  pending.innerHTML = '';
                  progress.innerHTML = '';
                  completed.innerHTML = '';

                  // Populate tables with new data
                  data.forEach(request => {
                     const formattedTime = formatTimeToAmPm(request.time); // Format the time
                     const row = `
               <tr data-patient-id="${request.patient_id}">
                  <td>${formattedTime}</td>
                  <td>${request.date}</td>
                  <td>${request.patient_id}</td>
                  <td>${request.doctor_id}</td>
               </tr>
            `;
                     if (request.state === 'pending') pending.innerHTML += row;
                     if (request.state === 'progress') progress.innerHTML += row;
                     if (request.state === 'completed') completed.innerHTML += row;
                  });
               })
               .catch(console.error);
         }, 5000);

         // Redirect to details page on row click
         document.addEventListener('click', e => {
            const row = e.target.closest('tr[data-patient-id]');
            if (row) {
               const patientID = row.getAttribute('data-patient-id');
               window.location.href = `medicationDetails?patientID=${patientID}`;
            }
         });
      </script>
</body>

</html>