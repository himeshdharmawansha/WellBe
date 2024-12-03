
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
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
            include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Admin/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="welcome-message">
                    <p>Welcome Mr. K.S.Perera</p>
                </div>
                <div class="cards-container">
                    <!-- Statistics Cards -->
                     <a href="../Appointments/ongoing.html">
                        <div class="card appointment">
                            <span class="circle-background">
                                <i class="fas icon fa-calendar-alt"></i>
                            </span>
                            <div>
                                <h1 class="figure">150</h1>
                                <span class="label">Appointments</span>
                            </div>
                        </div>
                     </a>
                    
                     <a href="../Patient/index.html">
                        <div class="card patients">
                            <span class="circle-background">
                                <i class="fas icon fa-user"></i>
                            </span>
                            <div>
                                <h1 class="figure">300 </h1>
                                <span class="label">Patients</span>
                            </div>
                        </div>
                     </a>
                    
                     <a href="../Doctor/index.html">
                        <div class="card doctors">
                            <span class="circle-background">
                                <i class="fas icon fa-user-md"></i>
                            </span>
                            <div>
                                <h1 class="figure">120 </h1>
                                <span class="label">Doctors</span>
                            </div>                      
                        </div>
                     </a>

                     <a href="../Pharmacists/index.html">
                        <div class="card pharmacists">
                            <span class="circle-background">
                                <i class="fas icon fa-pills"></i>
                            </span>
                            <div>
                                <h1 class="figure">25 </h1>
                                <span class="label">Pharmacists</span>
                            </div>          
                        </div>
                     </a>
                    
                    <a href="../LabTechs/index.html">
                        <div class="card lab-techs">
                            <span class="circle-background">
                                <i class="fas icon fa-vials"></i>
                            </span>
                            <div>
                                <h1 class="figure">34 </h1>
                                <span class="label">Lab Techs</span>
                            </div>
                        </div>
                    </a>
                    
                </div>
                <div class="content-container">
                    <!-- Today's Appointments and Patient Analysis -->
                        <div class="dashboard appointments">
                            <div class="header">
                                <h3>Today's Appointments</h3>
                                <a href="#" class="see-all">See all</a>
                            </div>  
                            <table class="appointment-table">
                                <tr>
                                    <td>
                                        <span class="doctor-name">Dr.K.G.Gunawardana</span>
                                        <span class="speciality">Cardiologist</span>
                                    </td>
                                    <td>
                                        <span class="time ongoing">Ongoing</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="doctor-name">Dr.K.G.Gunawardana</span>
                                        <span class="speciality">Cardiologist</span>
                                    </td>
                                    <td>
                                        <span class="time">12:30pm</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="doctor-name">Dr.K.G.Gunawardana</span>
                                        <span class="speciality">Cardiologist</span>
                                    </td>
                                    <td>
                                        <span class="time">1:00pm</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="doctor-name">Dr.K.G.Gunawardana</span>
                                        <span class="speciality">Cardiologist</span>
                                    </td>
                                    <td>
                                        <span class="time">3:00pm</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="doctor-name">Dr.K.G.Gunawardana</span>
                                        <span class="speciality">Cardiologist</span>
                                    </td>
                                    <td>
                                        <span class="time">5:00pm</span>
                                    </td>
                                </tr>
                            </table>  
                        </div>
                        <div class=" dashboard patient-analysis">
                            <h3>Patient Analysis</h3>
                            <div class="analysis-graph">
                                <!-- Graph image or chart can be placed here -->
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

    <script src="<?= ROOT ?>/assets/js/Admin/script.js"></script>
</body>
</html>
