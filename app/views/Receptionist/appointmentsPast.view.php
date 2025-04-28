
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Sessions</title>
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
                        <input type="text" id="searchInput" placeholder="Search by Doctor Name" />
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
                    <a  onclick="window.location.href='<?= ROOT ?>/Receptionist/todayAppointments'">
                        <span class="ongoing">Today</span>
                    </a>     
                    <a  onclick="window.location.href='<?= ROOT ?>/Receptionist/appointmentsUpcoming'">
                        <span class="upcoming">Upcoming</span>
                    </a>
                    <span class="past active">Past</span>
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
                                <td colspan="5">No Past Sessions</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>        
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the search input field
            const searchInput = document.getElementById('searchInput');

            // Get all the table rows inside the tbody
            const tableRows = document.querySelectorAll('tbody tr');

            // Add an event listener to trigger whenever a key is released in the search input
            searchInput.addEventListener('keyup', function() {
                // Convert the input value to lowercase for case-insensitive comparison
                const filter = searchInput.value.toLowerCase();
                // Loop through each row in the table
                tableRows.forEach(row => {
                    // Get the text content of the current row and convert it to lowercase
                    const rowText = row.textContent.toLowerCase();

                    // Check if the row contains the search term
                    if (rowText.includes(filter)) {
                        row.style.display = ''; // Show the row if it matches
                    } else {
                        row.style.display = 'none'; // Hide the row if it doesn't match
                    }
                });
            });
        });
    </script>
</body>
</html>
