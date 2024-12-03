<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/doctors.css">
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
            $pageTitle = "Doctors"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Admin/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="top-buttons">
                    <div class="search-bar">
                        <input type="text" placeholder="Search by Doctor ID" />
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="add-patient">
                        <a onclick="window.location.href='doctorForm1'">
                            <i class="fas fa-plus"></i>
                            <span class="add-text">Add New Doctor</span>
                        </a>
                    </div>
                </div>
                <div class="table-container">
                    <table class="patient-table">
                        <tr class="header-row">
                            <th>Doctor ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Contact No</th>
                        </tr>
                    </table>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
