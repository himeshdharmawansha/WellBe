
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medical Records</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/medicalreports.css">
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
            $pageTitle = "Doctor Portal"; // Set the text you want to display
            //include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            require '../app/views/Components/Doctor/header.php';
            ?>
      
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="container">
                   
                    <div class="search-date">
                        <input type="text" placeholder="Search by Date">
                       
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Medi_Rep_Id</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Doctor's Name</th>
                                    <th>Specialization</th>
                                    <th>View Record</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr onclick="window.location.href='medical_rec.php?nic=<?= urlencode($nic) ?>'">
                                    <td >Medi_Rec_001 </td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Cardiologist</td>
                                    <td><a href="<?= ROOT ?>/doctor/medical_record" class="view-button">View Record</a></td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Eye Surgeon</td>
                                    <td><a href="<?= ROOT ?>/doctor/medical_record" class="view-button">View Record</a></td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Dermetologist</td>
                                    <td><a href="<?= ROOT ?>/doctor/medical_record" class="view-button">View Record</a></td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>General</td>
                                    <td><a href="<?= ROOT ?>/doctor/medical_record" class="view-button">View Record</a></td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>General</td>
                                    <td><a href="<?= ROOT ?>/doctor/medical_record" class="view-button">View Record</a></td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Gneral</td>
                                    <td><a href="<?= ROOT ?>/doctor/medical_record" class="view-button">View Record</a></td>
                                </tr>
                                <!-- More rows here -->
                            </tbody>
                        </table>
                    </div>
                    
                    
                </div>     
    </div>
</body>
</html>
