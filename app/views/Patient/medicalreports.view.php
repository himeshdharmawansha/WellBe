<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medical Records</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/medicalreports.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">
        <?php
        $this->renderComponent('navbar', $active);
        ?>


        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Medical Reports"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">

                <div class="container">
                <div class="search-bar-container" style="margin-bottom: 20px; text-align: right;">
                        <input type="text" id="searchInput" placeholder="Search reports..." style="padding: 8px; width: 250px; border-radius: 5px; border: 1px solid #ccc;">
                    </div>
                    <?php if (!empty($pastRecords)) : ?>
                        <?php foreach ($pastRecords as $request) : ?>
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Medi_Rep_Id</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Doctor's Name</th>
                                            <th>Specialization</th>
                                            <th>Diagnosis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td onclick="window.location.href='medical_rec'">Medi_Rec_<?= htmlspecialchars($request->id) ?></td>
                                            <td><?= date('Y-m-d', strtotime($request->date)) ?></td>
                                            <td><?= htmlspecialchars($request->start_time) ?></td>
                                            <td><?= htmlspecialchars($request->doctor_first_name . " " . $request->doctor_last_name) ?></td>
                                            <td><?= htmlspecialchars($request->specialization) ?></td>
                                            <td><?= htmlspecialchars($request->diagnosis) ?></td>
                                        </tr>

                                        <!-- More rows here -->
                                    </tbody>
                                </table>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No medical reports available.</p>
                        <?php endif; ?>
                            </div>
                            <div class="pagination">
                                <span>Previous</span>
                                <div class="pages">
                                    <a href="#">1</a>
                                    <a href="#">2</a>
                                    <a href="#">3</a>
                                    <a href="#">4</a>
                                </div>
                                <span>Next</span>
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