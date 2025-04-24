
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Receptionist/dashboard.css" />
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
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/header.php';
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
                            <h1 class="figure"><?= $patientsCount ?></h1>
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
                            <h1 class="figure"><?= $pharmacistsCount ?></h1>
                            <span class="label">Pharmacists</span>
                        </div>          
                    </div>
                    
                    <div class="card lab-techs">
                        <span class="circle-background">
                            <i class="fas icon fa-vials"></i>
                        </span>
                        <div>
                            <h1 class="figure"><?= $labTechsCount ?></h1>
                            <span class="label">Lab Techs</span>
                        </div>
                    </div>    
                </div>

                <div class="content-container">
                    <!-- Today's Appointments and Patient Analysis -->
                        <div class="dashboard appointments">
                            <div class="header">
                                <h3>Today's Appointments</h3>
                                <a onclick="window.location.href='<?= ROOT ?>/Receptionist/todayAppointments'" class="see-all">See all</a>
                            </div>  
                            <!-- <table class="appointment-table">
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
                            </table>   -->
                            <table class="appointment-table">
                                <?php if (!empty($todaySessions)) : ?>
                                    <?php foreach ($todaySessions as $session) : ?>
                                        <?php
                                            $sessionTime = strtotime($session->start_time);
                                            $endTime = strtotime($session->end_time);
                                            $currentTime = strtotime(date('H:i:s'));
                                            $isOngoing = $currentTime >= $sessionTime && $currentTime <= $endTime;
                                            $formattedTime = date('g:i a', $sessionTime);
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="doctor-name">Dr. <?= htmlspecialchars($session->doctor_name) ?></span>
                                                <span class="speciality"><?= htmlspecialchars($session->specialization) ?></span>
                                            </td>
                                            <!-- <td>
                                                <span class="time"><?= date('g:i a', strtotime($session->start_time)) ?></span>
                                            </td> -->
                                            <td>
                                                <span class="time <?= $isOngoing ? 'ongoing' : '' ?>">
                                                    <?= $isOngoing ? 'Ongoing' : $formattedTime ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="2">No sessions today.</td></tr>
                                <?php endif; ?>
                            </table>
                        </div>            
                </div>
            </div>
        </div>
    </div>

    <script src="<?= ROOT ?>/assets/js/Admin/script.js"></script>
</body>
</html>
