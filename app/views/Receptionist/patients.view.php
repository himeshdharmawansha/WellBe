<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Patients</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/patients.css">
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
            $pageTitle = "Patients"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="top-buttons">
                    <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Search by Patient Name" />
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="add-patient">
                        <a onclick="window.location.href='patientForm1'">
                            <i class="fas fa-plus"></i>
                            <span class="add-text">Add New Patient</span>
                        </a>
                    </div>
                </div>
                <div class="table-container">
                    <table class="patient-table">
                        <tr class="header-row">
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Contact No</th>
                        </tr>
                        <?php if (!empty($patients)): ?>
                            <?php foreach ($patients as $patient): ?>
                                <tr onclick="window.location.href='<?= ROOT ?>/Receptionist/patientProfile?nic=<?= $patient->nic ?>'">
                                    <td><?= htmlspecialchars($patient->nic) ?></td>
                                    <td><?= htmlspecialchars($patient->name) ?></td>
                                    <td><?= htmlspecialchars($patient->age) ?></td>
                                    <td><?= htmlspecialchars($patient->contact) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No Patients found.</td>
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
