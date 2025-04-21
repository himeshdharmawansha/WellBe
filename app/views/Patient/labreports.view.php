<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Lab Reports</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/labreports.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .status-dropdown {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            background-color: #fff;
            font-size: 14px;
            color: #444;
            width: 100%;
        }

        .view-button {
            background-color: #118015;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .view-button:hover {
            background-color: #1b6d15;
        }
    </style>
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
            $pageTitle = "Lab Reports"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">

                <div class="container">
                    <div class="search-bar-container" style="margin-bottom: 20px; text-align: right;">
                        <input type="text" id="searchInput" placeholder="Search reports..." style="padding: 8px; width: 250px; border-radius: 5px; border: 1px solid #ccc;">
                    </div>

                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>
                                    <th>Report_Id</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Doctor's Name</th>
                                    <th>Test Name</th>
                                    <th>Status of Action</th>
                                    <th>Report</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($labReports)) : ?>
                                    <?php foreach ($labReports as $reports) : ?>
                                        <tr>
                                            <td onclick="window.location.href='Lab_download'">Lab_Rep_<?= htmlspecialchars($reports->id) ?></td>
                                            <td><?= date('Y-m-d', strtotime($reports->date)) ?></td>
                                            <td><?= htmlspecialchars($reports->start_time) ?></td>
                                            <td><?= htmlspecialchars($reports->doctor_first_name . " " . $reports->doctor_last_name) ?></td>
                                            <td><?= htmlspecialchars($reports->test_name) ?></td>
                                            <td>
                                                <?php
                                                $state = strtolower(trim($reports->state));
                                                ?>

                                                <?php if ($state === 'pending'): ?>
                                                    <span style="background-color: #fdd835; color: #000; padding: 5px 10px; border-radius: 5px;">
                                                        Pending
                                                    </span>
                                                <?php elseif ($state === 'view'): ?>
                                                    <button class="view-button">View</button>
                                                <?php else: ?>
                                                    <?= htmlspecialchars($reports->state) ?>
                                                <?php endif; ?>


                                            </td>
                                            <td class="report-cell"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7">No Lab reports available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>

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