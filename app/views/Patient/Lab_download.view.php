<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report Details</title>
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

        .view-button,
        .download-button {
            background-color: #118015;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .view-button:hover,
        .download-button:hover {
            background-color: #1b6d15;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #eef3ff;
            font-weight: bold;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination span,
        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            color: #333;
        }

        .pagination a:hover {
            background-color: #ddd;
            border-radius: 5px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 10px;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
            max-width: 1000px;
            border-radius: 5px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-content iframe,
        .modal-content img {
            width: 100%;
            height: 600px;
            border: none;
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
            $pageTitle = "Lab Report Details";
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="container">
                    <h3>Test Request ID: Lab_Req_<?= htmlspecialchars($request_id) ?></h3>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Test Name</th>
                                    <th>Specialization</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Report</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($labReports)) : ?>
                                    <?php foreach ($labReports as $report) : ?>
                                        <tr>
                                            <td><?= date('Y-m-d', strtotime($report->date)) ?></td>
                                            <td><?= htmlspecialchars($report->start_time) ?></td>
                                            <td><?= htmlspecialchars($report->test_name) ?></td>
                                            <td><?= htmlspecialchars($report->specialization) ?></td>
                                            <td><?= htmlspecialchars($report->priority) ?></td>
                                            <td>
                                                <?php
                                                $state = strtolower(trim($report->state));
                                                ?>
                                                <?php if ($state === 'pending'): ?>
                                                    <span style="background-color: #fdd835; color: #000; padding: 5px 10px; border-radius: 5px;">
                                                        Pending
                                                    </span>
                                                <?php else: ?>
                                                    <span style="background-color: #4caf50; color: #000; padding: 5px 10px; border-radius: 5px;">
                                                        <?= htmlspecialchars($report->state) ?>
                                                    </span>
                                                <?php endif; ?>

                                            </td>
                                            <td>
                                                <?php if ($state === 'completed' && !empty($report->file)): ?>
                                                    <a href="<?= ROOT ?>/assets/files/<?= htmlspecialchars($report->file) ?>" class="download-button" download>Download</a>
                                                    <button class="view-button" data-file="<?= ROOT ?>/assets/files/<?= htmlspecialchars($report->file) ?>">View</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7">No test details available for this request.</td>
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
        </div>
    </div>

    <!-- Modal for Viewing Files -->
    <div id="fileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="fileViewer"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-button');
            const modal = document.getElementById('fileModal');
            const fileViewer = document.getElementById('fileViewer');
            const closeBtn = document.querySelector('.close');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fileUrl = this.getAttribute('data-file');
                    const fileExtension = fileUrl.split('.').pop().toLowerCase();

                    // Determine how to display the file based on its extension
                    if (fileExtension === 'pdf') {
                        fileViewer.innerHTML = `<iframe src="${fileUrl}" title="File Viewer"></iframe>`;
                    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        fileViewer.innerHTML = `<img src="${fileUrl}" alt="File Preview">`;
                    } else {
                        fileViewer.innerHTML = `<p>Preview not available for this file type. Please download to view.</p>`;
                    }

                    modal.style.display = 'block';
                });
            });

            // Close modal when clicking the close button
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                fileViewer.innerHTML = ''; // Clear viewer content
            });

            // Close modal when clicking outside the modal content
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    fileViewer.innerHTML = ''; // Clear viewer content
                }
            });
        });
    </script>
</body>

</html>