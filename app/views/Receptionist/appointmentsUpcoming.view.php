<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
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
            include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Admin/header.php';
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
                        <span class="add-text">Schedule an appointment</span>
                    </div>
                </div>
                <div class="view-buttons">
                    <a onclick="window.location.href='appointmentsOngoing'">
                        <span class="ongoing">Ongoing</span>
                    </a>
                    <span class="upcoming active">Upcoming</span>
                    <span class="past">Past</span>
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
                        


                    </table>
                </div>
                

            </div>
                
        </div>
    </div>

</body>
</html>
