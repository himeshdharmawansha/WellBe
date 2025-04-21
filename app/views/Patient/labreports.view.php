<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Lab Requests</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/labreports.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #eef3ff;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination span, .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background-color: #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php $this->renderComponent('navbar', $active); ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Lab Requests";
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/header.php';
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
                                    <th>Request ID</th>
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($labRequests)) : ?>
                                    <?php foreach ($labRequests as $request) : ?>
                                        <tr onclick="window.location.href='<?= ROOT ?>/Patient/Lab_download/<?= htmlspecialchars($request->id) ?>'">
                                            <td>Lab_Req_<?= htmlspecialchars($request->id) ?></td>
                                            <td><?= htmlspecialchars($request->doctor_first_name) ?></td>
                                            <td><?= date('Y-m-d', strtotime($request->date)) ?></td>
                                            <td><?= htmlspecialchars($request->start_time) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4">No lab requests available.</td>
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