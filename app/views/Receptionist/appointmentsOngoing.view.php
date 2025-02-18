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
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/Receptionist/header.php';
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
                    <span class="ongoing active">Ongoing</span>
                    <a onclick="window.location.href='appointmentsUpcoming'">
                        <span class="upcoming">Upcoming</span>
                    </a>
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
                        <tr class="data-row" onclick="window.location.href='<?= ROOT ?>/Receptionist/appointmentQueue'">
                            <td>29/11/2024</td>
                            <td>11:00-13:00</td>
                            <td>Dr. Nishantha Samarasekera</td>
                            <td>10</td>
                            <td>6</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>12:00-13:30</td>
                            <td>Dr. Ravi Perera</td>
                            <td>25</td>
                            <td>10</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>13:00-15:00</td>
                            <td>Dr. Sandaruwani Peiris</td>
                            <td>18</td>
                            <td>15</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>14:30-16:00</td>
                            <td>Dr. Sonali Perera</td>
                            <td>20</td>
                            <td>8</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>15:00-17:00</td>
                            <td>Dr. Mana Silva</td>
                            <td>25</td>
                            <td>10</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>17:00-18:30</td>
                            <td>Dr. Thisuli Liyanarachchi</td>
                            <td>13</td>
                            <td>15</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>18:00-20:00</td>
                            <td>Dr. Kasun Yapa</td>
                            <td>30</td>
                            <td>5</td>
                        </tr>
                        <tr class="data-row">
                            <td>29/11/2024</td>
                            <td>19:30-21:00</td>
                            <td>Dr. Sonali Perera</td>
                            <td>12</td>
                            <td>8</td>
                        </tr>
                    </table>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>
