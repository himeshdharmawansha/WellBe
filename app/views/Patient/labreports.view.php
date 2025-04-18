
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
                                <tr>
                                    <td onclick="window.location.href='Lab_download'">Lab_Rep_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>FBC</td>
                                    <td><select class="status-dropdown">
                                        <option value="pending" selected>Pending</option>
                                        <option value="in-progress">In-Progress</option>
                                        <option value="completed">Completed</option>
                                    </select></td>
                                    <td class="report-cell"></td>
                                </tr>
                                <tr>
                                    <td>Lab_Rep_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>FBC</td>
                                    <td><select class="status-dropdown">
                                        <option value="pending" selected>Pending</option>
                                        <option value="in-progress">In-Progress</option>
                                        <option value="completed">Completed</option>
                                    </select></td>
                                    <td class="report-cell"></td>
                                </tr>
                                <tr>
                                    <td>Lab_Rep_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>ESR</td>
                                    <td><select class="status-dropdown">
                                        <option value="pending" selected>Pending</option>
                                        <option value="in-progress">In-Progress</option>
                                        <option value="completed">Completed</option>
                                    </select></td>
                                    <td class="report-cell"></td>
                                </tr>
                                <!-- More rows here -->
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
            function updateReportCells() {
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const statusSelect = row.querySelector('.status-dropdown');
                    const reportCell = row.querySelector('.report-cell');

                    statusSelect.addEventListener('change', function() {
                        const status = this.value;
                        if (status === 'completed') {
                            reportCell.innerHTML = '<button class="view-button">View</button>';
                        } else {
                            reportCell.innerHTML = '';
                        }
                    });

                    statusSelect.dispatchEvent(new Event('change'));
                });
            }

            updateReportCells();
        });
    </script>
</body>
</html>
