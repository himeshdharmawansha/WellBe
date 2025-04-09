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
         $pageTitle = "Test Requests";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>

         <!-- Dashboard Content -->
         <div class="dashboard-content">
            <div class="tabs">
               <button class="tab active" onclick="showTab('pending-requests')">New Requests</button>
               <button class="tab" onclick="showTab('ongoing-requests')">Ongoing Tests</button>
               <button class="tab" onclick="showTab('completed-requests')">Completed Tests</button>
            </div>

            <div class="search-container">
               <input type="text" class="search-input" placeholder="Search">
            </div>

            <!-- Requests Sections with Loading Placeholder -->
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
                     <tr>
                        <td colspan="4">Loading...</td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div id="ongoing-requests" class="requests-section">
               <table class="requests-table">
                  <thead>
                     <tr>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Doctor ID</th>
                     </tr>
                  </thead>
                  <tbody id="ongoing-requests-body">
                     <tr>
                        <td colspan="4">Loading...</td>
                     </tr>
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
                     <tr>
                        <td colspan="4">Loading...</td>
                     </tr>
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

   <script src="<?= ROOT ?>/assets/js/Lab/labTestRequest.js"></script>
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const rowsPerPage = 9;
         let currentPage = 1;
         let totalPages = 0;
         let currentTable = null;

         // Update request state on row click
         document.addEventListener('click', e => {
            const pendingRequestsSection = document.getElementById('pending-requests');
            const row = e.target.closest('tr[data-id]');

            if (row && pendingRequestsSection.contains(row)) {
               const requestID = row.getAttribute('data-id');

               fetch('<?= ROOT ?>/TestRequests/updateState', {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                        requestID: requestID,
                        state: 'ongoing',
                     }),
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        row.remove();
                        document.getElementById('ongoing-requests-body').appendChild(row);
                     } else {
                        alert('Failed to update state: ' + data.error);
                     }
                  })
                  .catch(console.error);
            }
         });

         // Setup pagination
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

         // Tab navigation
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

         // Polling for new data
         let isSearching = false;

         setInterval(() => {
            if (!isSearching) {
               fetch('<?= ROOT ?>/TestRequests/getRequestsJson')
                  .then(response => response.json())
                  .then(data => {
                     const pending = document.getElementById('pending-requests-body');
                     const ongoing = document.getElementById('ongoing-requests-body');
                     const completed = document.getElementById('completed-requests-body');

                     pending.innerHTML = '';
                     ongoing.innerHTML = '';
                     completed.innerHTML = '';

                     data.forEach(request => {
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

                        if (request.state === 'pending' || request.state === 'ongoing') pending.innerHTML += row;
                        if (request.state === 'ongoing') ongoing.innerHTML += row;
                        if (request.state === 'completed') completed.innerHTML += row;
                     });
                     setupPagination(currentTable);
                     displayPage(currentPage);
                  })
                  .catch(console.error);
            }
         }, 500);

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

                  fetch(`<?= ROOT ?>/TestRequests/searchRequestsByPatientId?patient_id=${searchTerm}`)
                     .then(response => response.json())
                     .then(data => {
                        const requestBodies = document.querySelectorAll('.requests-section tbody');
                        requestBodies.forEach(body => body.innerHTML = '');

                        if (data.length) {
                           data.forEach(request => {
                              const formattedTime = formatTimeToAmPm(request.time);

                              const row = `
                                 <tr data-id="${request.id}">
                                    <td>${formattedTime}</td>
                                    <td>${request.date}</td>
                                    <td>${request.patient_id}</td>
                                    <td>${request.doctor_id}</td>
                                 </tr>`;

                              if (request.state === 'pending' || request.state === 'ongoing') {
                                 document.querySelector("#pending-requests-body").innerHTML += row;
                              }
                              if (request.state === 'ongoing') {
                                 document.querySelector("#ongoing-requests-body").innerHTML += row;
                              }
                              if (request.state === 'completed') {
                                 document.querySelector("#completed-requests-body").innerHTML += row;
                              }
                           });
                        } else {
                           // Show "No results found" in all table bodies when search yields no results
                           requestBodies.forEach(body => {
                              body.innerHTML = `
                                 <tr>
                                    <td colspan="4" style="text-align: center;">No results found for "${searchTerm}"</td>
                                 </tr>`;
                           });
                        }

                        // Reset to the first page and reinitialize pagination
                        currentPage = 1;
                        setupPagination(currentTable);
                        displayPage(currentPage);
                     })
                     .catch(error => {
                        console.error("Search error:", error);
                        // Optionally handle fetch errors with a message
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
               }
            }, 300);
         });

         document.addEventListener('click', e => {
            const row = e.target.closest('tr[data-id]');
            if (row) {
               const requestID = row.getAttribute('data-id');
               const doctorID = row.querySelector('td:nth-child(4)').textContent.trim();
               const patientID = row.querySelector('td:nth-child(3)').textContent.trim();

               window.location.href = `labTestDetails?ID=${encodeURIComponent(requestID)}&patient_id=${encodeURIComponent(patientID)}`;
            }
         });
      });
   </script>
</body>
</html>