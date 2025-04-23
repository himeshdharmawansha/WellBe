<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/stats.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            $pageTitle = "Statistic Reports"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <div class = "content-container">
                <div class = "filter-card" data-type = "patient">
                    <span class = "section-title">Patients</span>
                    <div class = "filter-label">
                        <span class = "filters">Age Range: </span>
                        <input type = "text" name = "start-age"> 
                        <span class = "filters">To</span>
                        <input type = "text" name = "end-age">
                    </div>
                    <div class = "filter-label">
                        <span class = "filters">Gender: </span>
                        <select name = "filter-dropdown" class = "filter-dropdown">
                            <option value="All">All</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                    <div class="filter-label">
                        <span class="filters">Location: </span>
                        <input list="cities" class = "filter-dropdown" name="location" placeholder = "All">
                        <datalist id="cities">
                            <option value="All">
                            <option value="Colombo">
                            <option value="Kandy">
                            <option value="Galle">
                            <option value="Jaffna">
                            <option value="Negombo">
                            <option value="Gampaha">
                            <option value="Anuradhapura">
                            <option value="Batticaloa">
                            <option value="Kurunegala">
                            <option value="Matara">
                            <option value="Trincomalee">
                            <option value="Nuwara Eliya">
                            <option value="Ratnapura">
                            <option value="Polonnaruwa">
                            <option value="Badulla">
                            <option value="Hambantota">
                        </datalist>
                    </div> 
                        
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <div class = "filter-label">
                        <button type = "submit" class = "generate-btn" onclick="generateReport('patient')">Generate Report</button>
                    </div>                         
                </div>

                <!-- Profits Filter -->
                <div class="filter-card" data-type="profits">
                    <span class="section-title">Profits</span>
                    <div class="filter-label">
                        <span class="filters">Date Range:</span>
                        <input type="date" name="start-date">
                        <span class="filters">To</span>
                        <input type="date" name="end-date">
                    </div>
                    <div class="filter-label">
                        <span class="filters">Doctor's Name:</span>
                        <input type="text" name="doctor-name">
                    </div>
                    <div class="filter-label">
                        <button class="generate-btn" onclick="generateReport('profits')">Generate Report</button>
                    </div>
                </div>

                <!-- Appointments Filter -->
                <div class="filter-card" data-type="appointments">
                    <span class="section-title">Appointments</span>
                    <div class="filter-label">
                        <span class="filters">Doctor:</span>
                        <input type="text" name="doctor-name">
                    </div>
                    <div class="filter-label">
                        <span class="filters">Date Range:</span>
                        <input type="date" name="start-date">
                        <span class="filters">To</span>
                        <input type="date" name="end-date">
                    </div>
                    <div class="filter-label">
                        <button class="generate-btn" onclick="generateReport('appointments')">Generate Report</button>
                    </div>
                </div>
            </div>

            <!-- Shared Modal -->
            <div id="reportModal" class="popup">
                <div class="popup-content">
                    <span class="close-btn">&times;</span>
                    <canvas id="patientChart"></canvas>
                </div>
            </div>

            <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

            <script>
                function generateReport(type) {
                    const card = document.querySelector(`.filter-card[data-type='${type}']`);
                    const inputs = card.querySelectorAll("input, select");

                    // Build request body
                    let params = new URLSearchParams();
                    inputs.forEach(input => {
                        params.append(input.name, input.value);
                    });

                    console.log(`Sending request for ${type} with:`, params.toString());

                    fetch(`<?= ROOT ?>/admin/get${capitalize(type)}Stats`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: params.toString()
                    })
                    .then(response => response.json())
                    .then(data => {
                        renderChart(type, data);
                        openModal();
                    })
                    .catch(error => console.error("Error:", error));
                }

                function openModal() {
                    document.getElementById("reportModal").style.display = "flex";
                }

                // function closeModal() {
                //     document.getElementById("reportModal").style.display = "none";
                // }

                document.addEventListener("DOMContentLoaded", function () {
                    function closeModal() {
                        document.getElementById("reportModal").style.display = "none";
                    }

                // Close modal when clicking on the close button
                document.querySelector(".close-btn").addEventListener("click", closeModal);

                // Close modal when clicking outside the modal content
                window.addEventListener("click", function (event) {
                    let modal = document.getElementById("reportModal");
                        if (event.target === modal) {
                            closeModal();
                        }
                    });
                });

                function renderChart(type, data) {
                    const ctx = document.getElementById("patientChart").getContext("2d");

                    // Clean data handling based on report type
                    let labels = [];
                    let values = [];

                    if (type === "patient") {
                        data.forEach(entry => {
                            labels.push(entry.age);
                            values.push(entry.count);
                        });
                    } else if (type === "profits") {
                        data.forEach(entry => {
                            labels.push(entry.date);
                            values.push(entry.total_profit);
                        });
                    } else if (type === "appointments") {
                        data.forEach(entry => {
                            labels.push(entry.date);
                            values.push(entry.total_bookings);
                        });
                    } else if (type === "staff") {
                        data.forEach(entry => {
                            labels.push(entry.role);
                            values.push(entry.count);
                        });
                    }

                    // if (window.patientChart) {
                    //     window.patientChart.destroy();
                    // }

                    // Destroy the previous chart if it exists
                    if (window.patientChart && typeof window.patientChart.destroy === 'function') {
                        window.patientChart.destroy();
                    }

                    window.patientChart = new Chart(ctx, {
                        type: "line", // You can adjust chart type per report
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `${capitalize(type)} Report`,
                                data: values,
                                backgroundColor: "rgba(21, 165, 165, 0.5)",
                                borderColor: "rgb(21, 165, 165)",
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3 // Smoother curve
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                function capitalize(s) {
                    return s.charAt(0).toUpperCase() + s.slice(1);
                }
            </script>
        </div>
    </div>
</body>
</html>
