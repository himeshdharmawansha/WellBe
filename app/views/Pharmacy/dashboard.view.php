<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellBe</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/phamacistDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
    <div class="dashboard-container">
        <?php
        $this->renderComponent('navbar', $active);
        ?>
        <div class="main-content">
            <?php
            $pageTitle = "Dashboard";
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>
            <div class="dashboard-content">
                <div class="welcome-message">
                    <h4 class="welcome">Welcome <?= $_SESSION['USER']->first_name ?></h4>
                    <h4 class="date"><?php echo date('j M, Y'); ?></h4>
                </div>
                <div class="topbar">
                    <div class="search-bar">
                        <input type="text" id="search-input" placeholder="Search by medicine" />
                        <button type="button" id="search-button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <div class="cards-container">
                        <div class="card new-request" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fa-solid icon fa-hourglass-start"></i>
                            </span>
                            <p><?= $data['requestCounts']['pending'] ?> <br>New_Requests</p>
                        </div>
                        <div class="card completed" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-tasks"></i>
                            </span>
                            <p><?= $data['requestCounts']['completed'] ?> <br>Completed</p>
                        </div>
                    </div>
                </div>
                <div class="content-container">
                    <div class="dashboard messages">
                        <div class="header">
                            <h3>Medicines</h3>
                            <a href="medicines" class="see-all">See all</a>
                        </div>
                        <div class="table-container">
                            <table class="message-table">
                                <thead>
                                    <tr>
                                        <th style="padding-right: 155px;">Name</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="dashboard messages">
                        <div class="header">
                            <h3>Medication Requests</h3>
                            <a href="requests" class="see-all">See all</a>
                        </div>
                        <div class="table-container">
                            <table class="request-table">
                                <thead>
                                    <tr>
                                        <th style="padding-right: 140px;">Patient_ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="dashboard calendar-container">
                        <div id="curve_chart" style="width: 400px; height: 400px; padding:0%;margin:0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= ROOT ?>/assets/js/Pharmacy/phamacistDashboard.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            google.charts.load('current', {
                packages: ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);
                const startDate = `${thirtyDaysAgo.getFullYear()}-${String(thirtyDaysAgo.getMonth() + 1).padStart(2, '0')}-${String(thirtyDaysAgo.getDate()).padStart(2, '0')}`;
                const endDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

                fetch(`<?= ROOT ?>/Pharmacy/generateReport?start_date=${startDate}&end_date=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        const chartData = [
                            ['Medication', 'Usage', { role: 'tooltip', p: { html: true } }]
                        ];
                        data.medications.forEach(med => {
                            const truncatedName = med.medication_name.substring(0, 3);
                            const tooltip = `<div style="width:90px;"><strong>${med.medication_name}</strong><br>Usage: ${med.count}</div>`;
                            chartData.push([truncatedName, parseInt(med.count), tooltip]);
                        });

                        const options = {
                            title: 'Medication Usage',
                            vAxis: {},
                            colors: ['#1a73e8'],
                            legend: { position: 'bottom' },
                            tooltip: { isHtml: true }
                        };

                        const chart = new google.visualization.ColumnChart(document.getElementById('curve_chart'));
                        chart.draw(google.visualization.arrayToDataTable(chartData), options);
                    })
                    .catch(error => console.error('Error fetching medication usage data:', error));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('.request-table tbody');

            function fetchMedicationRequests() {
                fetch('<?= ROOT ?>/Pharmacy/medicationRequests')
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        data.forEach(request => {
                            html += `
                                <tr>
                                    <td>${request.patient_id}</td>
                                    <td><span class="status ${request.state}">${request.state}</span></td>
                                </tr>
                            `;
                        });
                        tableBody.innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            }

            fetchMedicationRequests();
            setInterval(fetchMedicationRequests, 3000);
        });

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

        function updateReceivedState() {
            fetch('<?= ROOT ?>/ChatController/loggedin')
                .catch(error => console.error("Error in loggedin :", error));
        }

        setInterval(updateReceivedState, 3000);

        document.addEventListener("DOMContentLoaded", function() {
            let shouldRefresh = true;
            const searchInput = document.getElementById('search-input');
            const tableBody = document.querySelector('.message-table tbody');

            fetchMedicines();

            setInterval(() => {
                if (shouldRefresh) {
                    fetchMedicines();
                }
            }, 5000);

            function fetchMedicines(query = '') {
                const url = query ?
                    `<?= ROOT ?>/Pharmacy/searchMedicine?query=${encodeURIComponent(query)}` :
                    `<?= ROOT ?>/Pharmacy/getStock`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(med => {
                                const row = document.createElement('tr');
                                const stateClass = med.state === 'In Stock' ? 'in-stock' : 'out-of-stock';
                                row.innerHTML = `
                                    <td>${med.generic_name}</td>
                                    <td><span class="stock-status ${stateClass}">${med.state}</span></td>
                                `;
                                tableBody.appendChild(row);
                            });
                        } else {
                            tableBody.innerHTML = '<tr><td colspan="2">No medicines found.</td></tr>';
                        }
                    })
                    .catch(error => console.error('Error fetching medicines:', error));
            }

            searchInput.addEventListener('keyup', function() {
                const query = searchInput.value.trim();

                if (query !== '') {
                    shouldRefresh = false;
                    fetchMedicines(query);
                } else {
                    shouldRefresh = true;
                    fetchMedicines();
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('search-input');
            const tableBody = document.querySelector('.message-table tbody');

            searchInput.addEventListener('keyup', function() {
                fetchMedicines(searchInput.value);
            });

            function fetchMedicines(query = '') {
                fetch(`<?= ROOT ?>/Pharmacy/searchMedicine?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(med => {
                                const row = document.createElement('tr');
                                const stateClass = med.state === 'In Stock' ? 'in-stock' : 'out-of-stock';
                                row.innerHTML = `
                                    <td>${med.generic_name}</td>
                                    <td><span class="stock-status ${stateClass}">${med.state}</span></td>
                                `;
                                tableBody.appendChild(row);
                            });
                        } else {
                            tableBody.innerHTML = '<tr><td colspan="2">No medicines found.</td></tr>';
                        }
                    })
                    .catch(error => console.error('Error fetching medicines:', error));
            }

            fetchMedicines();
        });
    </script>
</body>

</html>