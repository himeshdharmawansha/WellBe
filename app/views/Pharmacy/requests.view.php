<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>WELLBE</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/medicationRequestList.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
      <?php $this->renderComponent('navbar', $active); ?>
      <div class="main-content">
         <?php
         $pageTitle = "Medication Requests";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>
         <div class="dashboard-content">
            <div class="tabs">
               <button class="tab active" onclick="showTab('pending-requests')">Pending Requests</button>
               <button class="tab" onclick="showTab('completed-requests')">Completed Requests</button>
            </div>
            <div class="search-container">
               <input type="text" class="search-input" placeholder="Search PatientID" id="searchPatientId" oninput="filterResults()">
            </div>
            <div id="pending-requests" class="requests-section active">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Request ID</th>
                        <th>Patient ID</th>
                        <th>Doctor's Name</th>
                        <th>Date</th>
                        <th>Time</th>
                     </tr>
                  </thead>
                  <tbody id="pending-requests-body">
                     <?php if (empty($data['pendingRequests'])) : ?>
                        <tr>
                           <td colspan="4" style="text-align: center;">No pending requests found.</td>
                        </tr>
                     <?php else : ?>
                        <?php foreach ($data['pendingRequests'] as $request) : ?>
                           <tr data-id="<?= esc($request['id']) ?>" data-doctor-id="<?= esc($request['doctor_id']) ?>">
                              <td><?= esc($request['id']) ?></td>
                              <td><?= esc($request['patient_id']) ?></td>
                              <td><?= esc($request['first_name']) ?></td>
                              <td><?= esc($request['date_t']) ?></td>
                              <td><?= date('h:i A', strtotime($request['time'])) ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
            <div id="completed-requests" class="requests-section">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Request ID</th>
                        <th>Patient ID</th>
                        <th>Doctor's Name</th>
                        <th>Date</th>
                        <th>Time</th>
                     </tr>
                  </thead>
                  <tbody id="completed-requests-body">
                     <?php if (empty($data['completedRequests'])) : ?>
                        <tr>
                           <td colspan="4" style="text-align: center;">No completed requests found.</td>
                        </tr>
                     <?php else : ?>
                        <?php foreach ($data['completedRequests'] as $request) : ?>
                           <tr data-id="<?= esc($request['id']) ?>" data-doctor-id="<?= esc($request['doctor_id']) ?>">
                              <td><?= esc($request['id']) ?></td>
                              <td><?= esc($request['patient_id']) ?></td>
                              <td><?= esc($request['first_name']) ?></td>
                              <td><?= esc($request['date_t']) ?></td>
                              <td><?= date('h:i A', strtotime($request['time'])) ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
            <div class="pagination" id="pagination-controls">
               <button class="pagination-btn" onclick="changePage(-1)">Previous</button>
               <span id="pagination-pages"></span>
               <button class="pagination-btn" onclick="changePage(1)">Next</button>
            </div>
         </div>
      </div>
   </div>
   <script src="<?= ROOT ?>/assets/js/Pharmacy/medicationRequestList.js"></script>
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const rowsPerPage = 9;
         let currentPage = 1;
         let totalPages = 0;
         let currentTable = null;

         function setupPagination(table) {
            if (!table) return;

            const rows = table.querySelectorAll('tbody tr');
            totalPages = Math.ceil(rows.length / rowsPerPage);

            const paginationContainer = document.querySelector('.pagination');
            paginationContainer.innerHTML = '';

            const createButton = (text, className, onClick, disabled = false) => {
               const button = document.createElement('button');
               button.textContent = text;
               button.className = className;
               button.disabled = disabled;
               button.addEventListener('click', onClick);
               return button;
            };

            const prevButton = createButton('Previous', 'pagination-btn', () => {
               if (currentPage > 1) {
                  currentPage--;
                  displayPage(currentPage);
               }
            }, currentPage === 1);

            paginationContainer.appendChild(prevButton);

            for (let i = 1; i <= totalPages; i++) {
               const pageButton = createButton(i, `pagination-page ${i === currentPage ? 'active' : ''}`, () => {
                  currentPage = i;
                  displayPage(currentPage);
               });
               paginationContainer.appendChild(pageButton);
            }

            const nextButton = createButton('Next', 'pagination-btn', () => {
               if (currentPage < totalPages) {
                  currentPage++;
                  displayPage(currentPage);
               }
            }, currentPage === totalPages);

            paginationContainer.appendChild(nextButton);
         }

         function displayPage(page) {
            if (!currentTable) return;

            const rows = currentTable.querySelectorAll('tbody tr');
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
               row.style.display = index >= start && index < end ? '' : 'none';
            });

            const pageButtons = document.querySelectorAll('.pagination-page');
            pageButtons.forEach(button => {
               button.classList.toggle('active', parseInt(button.textContent) === page);
            });
         }

         window.showTab = function(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.requests-section').forEach(section => section.classList.remove('active'));

            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
            const selectedSection = document.getElementById(tabId);
            selectedSection.classList.add('active');

            currentTable = selectedSection.querySelector('.requests-table');
            currentPage = 1;
            setupPagination(currentTable);
            displayPage(currentPage);
         };

         window.showTab('pending-requests');

         let isSearching = false;

         setInterval(() => {
            if (!isSearching) {
               fetch('<?= ROOT ?>/Pharmacy/getRequestsJson')
                  .then(response => response.json())
                  .then(data => {
                     const pending = document.getElementById('pending-requests-body');
                     const completed = document.getElementById('completed-requests-body');

                     pending.innerHTML = '';
                     completed.innerHTML = '';

                     data.forEach(request => {
                        const formattedTime = formatTimeToAmPm(request.time);
                        const row = `
                           <tr data-id="${request.id}" data-doctor-id="${request.doctor_id}">
                              <td>${request.id}</td>
                              <td>${request.patient_id}</td>
                              <td>${request.first_name}</td>
                              <td>${request.date_t}</td>
                              <td>${formattedTime}</td>
                           </tr>`;

                        if (request.state === 'pending') pending.innerHTML += row;
                        if (request.state === 'completed') completed.innerHTML += row;
                     });

                     setupPagination(currentTable);
                     displayPage(currentPage);
                  })
                  .catch(error => {
                     console.error('Error fetching requests:', error);
                     const pending = document.getElementById('pending-requests-body');
                     const completed = document.getElementById('completed-requests-body');
                     pending.innerHTML = '<tr><td colspan="4" style="text-align: center;">Error loading requests.</td></tr>';
                     completed.innerHTML = '<tr><td colspan="4" style="text-align: center;">Error loading requests.</td></tr>';
                  });
            }
         }, 3000);

         function formatTimeToAmPm(time) {
            const [hours, minutes, seconds] = time.split(':');
            const date = new Date();
            date.setHours(hours, minutes, seconds || 0);
            return date.toLocaleTimeString('en-US', {
               hour: '2-digit',
               minute: '2-digit',
               hour12: true
            });
         }

         let debounceTimeout;
         document.querySelector('.search-input').addEventListener('input', function(e) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
               const searchTerm = e.target.value.trim();
               if (searchTerm) {
                  isSearching = true;

                  fetch(`<?= ROOT ?>/MedicationRequests/searchRequestsByPatientId?patient_id=${searchTerm}`)
                     .then(response => response.json())
                     .then(data => {
                        const requestBodies = document.querySelectorAll('.requests-section tbody');
                        requestBodies.forEach(body => body.innerHTML = '');

                        if (data.length) {
                           data.forEach(request => {
                              const formattedTime = formatTimeToAmPm(request.time);
                              const row = `
                                 <tr data-id="${request.id}" data-doctor-id="${request.doctor_id}">
                                    <td>${request.id}</td>
                                    <td>${request.patient_id}</td>
                                    <td>${request.first_name}</td>
                                    <td>${request.date_t}</td>
                                    <td>${formattedTime}</td>
                                 </tr>`;
                              if (request.state === "completed") {
                                 document.querySelector("#completed-requests-body").innerHTML += row;
                              } else {
                                 document.querySelector("#pending-requests-body").innerHTML += row;
                              }
                           });
                        } else {
                           requestBodies.forEach(body => {
                              body.innerHTML = `
                                 <tr>
                                    <td colspan="4" style="text-align: center;">No results found for "${searchTerm}"</td>
                                 </tr>`;
                           });
                        }

                        currentPage = 1;
                        setupPagination(currentTable);
                        displayPage(currentPage);
                     })
                     .catch(error => {
                        console.error("Search error:", error);
                        const requestBodies = document.querySelectorAll('.requests-section tbody');
                        requestBodies.forEach(body => {
                           body.innerHTML = `
                              <tr>
                                 <td colspan="4" style="text-align: center;">Error occurred while searching</td>
                              </tr>`;
                        });
                        currentPage = 1;
                        setupPagination(currentTable);
                        displayPage(currentPage);
                     });
               } else {
                  isSearching = false;
                  updatePage();
               }
            }, 300);
         });

         document.addEventListener('click', e => {
            const row = e.target.closest('tr[data-id]');
            if (row) {
               const requestID = row.getAttribute('data-id');
               const doctorID = row.getAttribute('data-doctor-id');
               const patientID = row.querySelector('td:nth-child(2)').textContent.trim();

               window.location.href = `medicationDetails?ID=${encodeURIComponent(requestID)}&doctor_id=${encodeURIComponent(doctorID)}&patient_id=${encodeURIComponent(patientID)}`;
            }
         });
      });
   </script>
</body>

</html>