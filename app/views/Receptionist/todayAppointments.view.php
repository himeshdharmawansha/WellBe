<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today Sessions</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/appointments.css">
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
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="top-buttons">
                    <div class="search-bar">
                        <input type="text" placeholder="Search by Appointment ID" />
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="add-appointment">
                        <i class="fas fa-plus"></i>
                        <a class="add-text" onclick = "window.location.href='<?= ROOT ?>/Receptionist/scheduleAppointment'" >Schedule an appointment</a>
                    </div>
                </div>
                <div class="view-buttons">
                    <span class="ongoing active">Today</span>
                    <a onclick="window.location.href='<?= ROOT ?>/Receptionist/appointmentsUpcoming'">
                        <span class="upcoming">Upcoming</span>
                    </a>
                    <a  onclick="window.location.href='<?= ROOT ?>/Receptionist/appointmentsPast'">
                        <span class="past">Past</span>
                    </a>
                </div>

                <div class="table-container">
                    <table class="appointment-table">
                        <tr class="header-row">
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor's Name</th>
                            <th>Booked Slots</th>
                            <th>Available Slots</th>
                        </tr>
                        <?php if (!empty($today_sessions)): ?>
                            <?php foreach ($today_sessions as $session): ?>
                                <tr onclick="window.location.href='<?= ROOT ?>/Receptionist/appointmentQueue?slot_id=<?= $session->slot_id ?>&doctor_id=<?= $session->doctor_id ?>'">
                                    <td><?= date('d/m/Y', strtotime($session->date)) ?></td>
                                    <td><?= substr($session->start_time, 0, 5) ?> - <?= substr($session->end_time, 0, 5) ?></td>
                                    <td>Dr. <?= htmlspecialchars($session->doctor_name) ?></td>
                                    <td><?= htmlspecialchars($session->booked_slots) ?></td>
                                    <td><?= htmlspecialchars(15 - $session->booked_slots) ?></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No sessions found for today</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>
