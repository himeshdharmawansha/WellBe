


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
                   
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Medi_Rep_Id</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Doctor's Name</th>
                                    <th>Specialization</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td onclick="window.location.href='medical_rec'">Medi_Rec_001 </td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Cardiologist</td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Eye Surgeon</td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Dermetologist</td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>General</td>
                                </tr>
                                <tr>
                                    <td>Medi_Rec_001</td>
                                    <td>12/08/2024</td>
                                    <td>7:00 - 10:00</td>
                                    <td>Dr. K. G. Gunawardana</td>
                                    <td>Gneral</td>
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
</body>
</html>
