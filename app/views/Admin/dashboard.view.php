
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            $pageTitle = "Dashboard"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="welcome-message">
                    <p>Welcome <?php echo htmlspecialchars($_SESSION['USER']->first_name); ?></p>
                </div>
                <div class="cards-container">
                    <!-- Statistics Cards -->
                    <div class="card appointment">
                        <span class="circle-background">
                            <i class="fas icon fa-calendar-alt"></i>
                        </span>
                        <div>
                            <h1 class="figure"><?= $todayAppointmentsCount ?></h1>
                            <span class="label">Appointments</span>
                        </div>
                    </div>
                     
                    <div class="card patients">
                        <span class="circle-background">
                            <i class="fas icon fa-user"></i>
                        </span>
                        <div>
                            <h1 class="figure"><?= $patientsCount ?> </h1>
                            <span class="label">Patients</span>
                        </div>
                    </div>
                    
                    <div class="card doctors">
                        <span class="circle-background">
                            <i class="fas icon fa-user-md"></i>
                        </span>
                        <div>
                            <h1 class="figure"><?= $doctorsCount ?> </h1>
                            <span class="label">Doctors</span>
                        </div>                      
                    </div>

                    <div class="card pharmacists">
                        <span class="circle-background">
                            <i class="fas icon fa-pills"></i>
                        </span>
                        <div>
                            <h1 class="figure"><?= $pharmacistsCount ?> </h1>
                            <span class="label">Pharmacists</span>
                        </div>          
                    </div>
                    
                    <div class="card lab-techs">
                        <span class="circle-background">
                            <i class="fas icon fa-vials"></i>
                        </span>
                        <div>
                            <h1 class="figure"><?= $labTechsCount ?> </h1>
                            <span class="label">Lab Techs</span>
                        </div>
                    </div>
                </div>
                <div class="content-container">
                    <div class="dashboard appointments">
                        <div class="header">
                            <h3>Add New Users</h3>
                        </div>
                        <div class = "quick-buttons">
                            <button class = "new-doc" onclick = "window.location.href='<?= ROOT ?>/Admin/doctorForm1'"> New Doctor </button>
                            <button class = "new-labtech" onclick = "window.location.href='<?= ROOT ?>/Admin/labTechForm1'"> New Lab Technician </button>
                            <button class = "new-pharmacist" onclick = "window.location.href='<?= ROOT ?>/Admin/pharmacistForm1'"> New Pharmacist </button>
                        </div>

                    </div>
                    <div class=" dashboard patient-analysis">
                        <h3>Patient Analysis</h3>
                        <div class="analysis-graph">
                            <canvas id="staffHistogram" width="400" height="300"></canvas> 
                        </div>
                    </div>
                    <div class="dashboard calendar-container">
                        <div class="calendar-header">
                            <h3>Calendar</h3>
                            <div class="calendar-nav">
                                <button id="prevMonth">&lt;</button>
                                <span id="monthYear"></span>
                                <button id="nextMonth">&gt;</button>
                            </div>
                        </div>
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th>S</th>
                                    <th>M</th>
                                    <th>T</th>
                                    <th>W</th>
                                    <th>T</th>
                                    <th>F</th>
                                    <th>S</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body">
                                    <!-- Calendar Dates will be generated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
        const ctx = document.getElementById('staffHistogram').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Doctors', 'Lab Technicians', 'Pharmacists'],
                datasets: [{
                    label: 'Total Registered',
                    data: [<?= $doctorsCount ?>, <?= $labTechsCount ?>, <?= $pharmacistsCount ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // Prevent decimal tick values
                        }
                    }
                }
            }
        });
    </script>

    <script src="<?= ROOT ?>/assets/js/Admin/script.js"></script>
</body>
</html>
