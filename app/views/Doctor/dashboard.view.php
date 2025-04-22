<?php
$totalAppointments = 0;
$newPatients = 0;
$returningPatients = 0;

// Check and loop through today's appointments
if (!empty($data['today_appointments'])) {
    $totalAppointments = count($data['today_appointments']);

    foreach ($data['today_appointments'] as $appointment) {
        if ($appointment->patient_type === 'NEW') {
            $newPatients++;
        } elseif ($appointment->patient_type === 'RETURNING') {
            $returningPatients++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/doc_dashboard.css?v=1.1">

</head>

<body>
    <div class=" h-full" style="display: flex;">
        <?php
        $this->renderComponent('navbar', $active);
        ?>


        <div class="main-content">

            <?php
            $pageTitle = "Doctor Portal"; // Set the text you want to display
            //include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>


            <div class="items dashboard-content">


                <!-- Welcome Card -->
                <div class="dashboard-card">
                    <div class="welcome-section">
                        <div class="welcome-info">
                            
                            <div>
                                <p class="welcome-text">Welcome</p>
                                <p class="doctor-name">
                                    Dr. <?= htmlspecialchars($_SESSION['USER']->first_name); ?> <?= htmlspecialchars($_SESSION['USER']->last_name); ?>
                                </p>
                            </div>
                        </div>

                        <div class="appointment-summary">
                            Dr. <?= htmlspecialchars($_SESSION['USER']->first_name); ?>, you have
                            <strong><?= $totalAppointments; ?></strong> appointments today
                        </div>

                        <div class="patient-counts">
                            <p>New Patients: <strong><?= $newPatients; ?></strong></p>
                            <p>Returning Patients: <strong><?= $returningPatients; ?></strong></p>
                        </div>

                        <div class="checkup-section">
                            <p class="checkup-title">Start Patient Check-up</p>
                            <a href="<?= ROOT ?>/doctor/today_checkups" class="start-btn">Start</a>
                        </div>
                    </div>
                </div>



                <div class="container2">
                    <div class="graph">
                        <?php
                        $this->renderChart('chart');
                        ?>
                        <?php
                        $this->renderCalender('calender');
                        ?>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

</html>

<?php
ob_end_flush(); // Flush the buffer and send output
?>