
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report Download</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/Lab_download.css?v=<?= time() ?>">
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
            $pageTitle = "Lab Report Download"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                
                <div class="report-container">
                    <p>Lab Report - 11/2/2024<hr></p>
                    
                    <div class="report">
                        <img src="../assests/lab_report.jpeg">

                    </div>
                </div >
                <div class="button-container">
                    <button class="action-button">Download Report</button>
                    <button class="action-button">Share Report</button>
                </div>
            </div>
    </div>
</body>
</html>
